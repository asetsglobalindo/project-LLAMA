#!/usr/bin/env python3
"""
Script untuk mengambil data SPACES dari API ASETS
URL: https://service.asets.id/api/space-available
"""

import requests
import sqlite3
import json
import time
from datetime import datetime
from typing import Dict, List, Any
import logging

# Setup logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler('spaces_fetch.log'),
        logging.StreamHandler()
    ]
)
logger = logging.getLogger(__name__)

class SpacesDataFetcher:
    def __init__(self, db_path: str = "spaces_data.db"):
        """
        Initialize the spaces data fetcher
        
        Args:
            db_path: Path to SQLite database file
        """
        self.db_path = db_path
        self.spaces_url = "https://service.asets.id/api/space-available"
        self.session = requests.Session()
        self.session.headers.update({
            'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        })
        
        # Initialize database
        self.init_database()
    
    def init_database(self):
        """Initialize SQLite database with spaces tables"""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        # Create spaces table
        cursor.execute('''
            CREATE TABLE IF NOT EXISTS spaces (
                id INTEGER PRIMARY KEY,
                code TEXT,
                name TEXT,
                type TEXT,
                price REAL,
                price_type TEXT,
                size_sqm INTEGER,
                min_period TEXT,
                listing_id INTEGER,
                listing_address TEXT,
                listing_city TEXT,
                source TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ''')
        
        # Create media table for spaces
        cursor.execute('''
            CREATE TABLE IF NOT EXISTS media (
                id INTEGER PRIMARY KEY,
                file TEXT,
                url TEXT,
                space_id INTEGER,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (space_id) REFERENCES spaces (id)
            )
        ''')
        
        # Create indexes for better performance
        cursor.execute('CREATE INDEX IF NOT EXISTS idx_spaces_listing ON spaces(listing_id)')
        cursor.execute('CREATE INDEX IF NOT EXISTS idx_spaces_type ON spaces(type)')
        cursor.execute('CREATE INDEX IF NOT EXISTS idx_spaces_price ON spaces(price)')
        cursor.execute('CREATE INDEX IF NOT EXISTS idx_media_space ON media(space_id)')
        
        conn.commit()
        conn.close()
        logger.info("Database initialized successfully")
    
    def fetch_all_pages(self, params: Dict = None) -> List[Dict]:
        """
        Fetch all pages from spaces API endpoint
        
        Args:
            params: Additional parameters
            
        Returns:
            List of all spaces data from all pages
        """
        all_data = []
        page = 1
        max_page = 1
        
        if params is None:
            params = {}
        
        while page <= max_page:
            try:
                params['page'] = page
                logger.info(f"Fetching page {page} from {self.spaces_url}")
                
                response = self.session.get(self.spaces_url, params=params, timeout=30)
                response.raise_for_status()
                
                data = response.json()
                
                if not data.get('success', False):
                    logger.error(f"API returned success=false for page {page}")
                    break
                
                # Update max_page from response
                if page == 1:
                    max_page = data.get('requested', {}).get('max_page', 1)
                    logger.info(f"Total pages to fetch: {max_page}")
                
                # Add data to our list
                page_data = data.get('data', [])
                all_data.extend(page_data)
                
                logger.info(f"Fetched {len(page_data)} spaces from page {page}")
                
                page += 1
                
                # Small delay to be respectful to the API
                time.sleep(0.5)
                
            except requests.exceptions.RequestException as e:
                logger.error(f"Error fetching page {page}: {e}")
                break
            except json.JSONDecodeError as e:
                logger.error(f"Error parsing JSON for page {page}: {e}")
                break
            except Exception as e:
                logger.error(f"Unexpected error on page {page}: {e}")
                break
        
        logger.info(f"Total spaces fetched: {len(all_data)}")
        return all_data
    
    def save_spaces_data(self, spaces_data: List[Dict]):
        """Save spaces data to database"""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        saved_count = 0
        
        for item in spaces_data:
            try:
                space = item.get('space', {})
                listing = item.get('listing', {})
                media_list = item.get('media', [])
                
                # Insert or update space
                cursor.execute('''
                    INSERT OR REPLACE INTO spaces 
                    (id, code, name, type, price, price_type, size_sqm, min_period, 
                     listing_id, listing_address, listing_city, source, updated_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ''', (
                    space.get('id'),
                    space.get('code'),
                    space.get('name'),
                    space.get('type'),
                    float(space.get('price', 0)) if space.get('price') else 0,
                    space.get('price_type'),
                    space.get('size_sqm'),
                    space.get('min_period'),
                    listing.get('id'),
                    listing.get('address'),
                    listing.get('city'),
                    item.get('source'),
                    datetime.now().isoformat()
                ))
                
                space_id = space.get('id')
                
                # Save media for this space
                for media in media_list:
                    cursor.execute('''
                        INSERT OR REPLACE INTO media 
                        (id, file, url, space_id)
                        VALUES (?, ?, ?, ?)
                    ''', (
                        media.get('id'),
                        media.get('file'),
                        media.get('url'),
                        space_id
                    ))
                
                saved_count += 1
                
            except Exception as e:
                logger.error(f"Error saving space {item.get('space', {}).get('id', 'unknown')}: {e}")
        
        conn.commit()
        conn.close()
        logger.info(f"Saved {saved_count} spaces to database")
    
    def fetch_all_data(self):
        """Fetch all spaces data from API and save to database"""
        logger.info("Starting spaces data fetch process...")
        
        # Fetch spaces data
        spaces_data = self.fetch_all_pages()
        self.save_spaces_data(spaces_data)
        
        logger.info("Spaces data fetch process completed!")
    
    def get_database_stats(self):
        """Get statistics about the spaces data in database"""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        # Get counts
        cursor.execute("SELECT COUNT(*) FROM spaces")
        spaces_count = cursor.fetchone()[0]
        
        cursor.execute("SELECT COUNT(*) FROM media")
        media_count = cursor.fetchone()[0]
        
        # Get spaces by type
        cursor.execute("SELECT type, COUNT(*) FROM spaces GROUP BY type ORDER BY COUNT(*) DESC")
        spaces_by_type = cursor.fetchall()
        
        # Get spaces by source
        cursor.execute("SELECT source, COUNT(*) FROM spaces GROUP BY source")
        spaces_by_source = cursor.fetchall()
        
        # Get price statistics
        cursor.execute("SELECT MIN(price), MAX(price), AVG(price) FROM spaces WHERE price > 0")
        price_stats = cursor.fetchone()
        
        # Get size statistics
        cursor.execute("SELECT MIN(size_sqm), MAX(size_sqm), AVG(size_sqm) FROM spaces WHERE size_sqm > 0")
        size_stats = cursor.fetchone()
        
        conn.close()
        
        stats = {
            'spaces_count': spaces_count,
            'media_count': media_count,
            'spaces_by_type': dict(spaces_by_type),
            'spaces_by_source': dict(spaces_by_source),
            'price_stats': {
                'min': price_stats[0],
                'max': price_stats[1],
                'avg': price_stats[2]
            },
            'size_stats': {
                'min': size_stats[0],
                'max': size_stats[1],
                'avg': size_stats[2]
            }
        }
        
        return stats
    
    def export_to_json(self, output_file: str = "spaces_export.json"):
        """Export all spaces data to JSON file"""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        # Get all spaces with their media
        cursor.execute('''
            SELECT s.*, GROUP_CONCAT(m.id || '|' || m.file || '|' || m.url, '||') as media
            FROM spaces s
            LEFT JOIN media m ON s.id = m.space_id
            GROUP BY s.id
        ''')
        
        spaces = []
        for row in cursor.fetchall():
            space = {
                'id': row[0],
                'code': row[1],
                'name': row[2],
                'type': row[3],
                'price': row[4],
                'price_type': row[5],
                'size_sqm': row[6],
                'min_period': row[7],
                'listing_id': row[8],
                'listing_address': row[9],
                'listing_city': row[10],
                'source': row[11],
                'created_at': row[12],
                'updated_at': row[13],
                'media': []
            }
            
            if row[14]:  # media data
                for media_item in row[14].split('||'):
                    if media_item:
                        parts = media_item.split('|')
                        if len(parts) >= 3:
                            space['media'].append({
                                'id': parts[0],
                                'file': parts[1],
                                'url': parts[2]
                            })
            
            spaces.append(space)
        
        conn.close()
        
        # Create export data
        export_data = {
            'export_timestamp': datetime.now().isoformat(),
            'spaces': spaces
        }
        
        # Save to JSON file
        with open(output_file, 'w', encoding='utf-8') as f:
            json.dump(export_data, f, indent=2, ensure_ascii=False)
        
        logger.info(f"Spaces data exported to {output_file}")
        return export_data

def main():
    """Main function to run the spaces data fetcher"""
    try:
        # Initialize fetcher
        fetcher = SpacesDataFetcher()
        
        # Fetch all data
        fetcher.fetch_all_data()
        
        # Get and display statistics
        stats = fetcher.get_database_stats()
        
        print("\n" + "="*50)
        print("SPACES DATA FETCH COMPLETED!")
        print("="*50)
        print(f"Total Spaces: {stats['spaces_count']}")
        print(f"Total Media: {stats['media_count']}")
        print("\nSpaces by Type:")
        for space_type, count in stats['spaces_by_type'].items():
            print(f"  {space_type}: {count}")
        print("\nSpaces by Source:")
        for source, count in stats['spaces_by_source'].items():
            print(f"  {source}: {count}")
        print("\nPrice Statistics:")
        print(f"  Min Price: Rp {stats['price_stats']['min']:,.0f}")
        print(f"  Max Price: Rp {stats['price_stats']['max']:,.0f}")
        print(f"  Avg Price: Rp {stats['price_stats']['avg']:,.0f}")
        print("\nSize Statistics:")
        print(f"  Min Size: {stats['size_stats']['min']} sqm")
        print(f"  Max Size: {stats['size_stats']['max']} sqm")
        print(f"  Avg Size: {stats['size_stats']['avg']:.1f} sqm")
        print("="*50)
        
        # Export to JSON
        fetcher.export_to_json()
        
    except Exception as e:
        logger.error(f"Error in main process: {e}")
        raise

if __name__ == "__main__":
    main()






