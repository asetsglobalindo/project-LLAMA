#!/usr/bin/env python3
"""
Script untuk mengambil data dari API ASETS dan menyimpannya ke database SQL
Mengambil data dari:
1. URL_LISTINGS = "https://api.asets.id/api/listings"
2. URL_SPACES = "https://service.asets.id/api/space-available"
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
        logging.FileHandler('api_fetch.log'),
        logging.StreamHandler()
    ]
)
logger = logging.getLogger(__name__)

class AsetsDataFetcher:
    def __init__(self, db_path: str = "asets_data.db"):
        """
        Initialize the data fetcher
        
        Args:
            db_path: Path to SQLite database file
        """
        self.db_path = db_path
        self.listings_url = "https://api.asets.id/api/listings"
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
        """Initialize SQLite database with required tables"""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        # Create listings table
        cursor.execute('''
            CREATE TABLE IF NOT EXISTS listings (
                id INTEGER PRIMARY KEY,
                name TEXT NOT NULL,
                slug TEXT,
                spaces INTEGER DEFAULT 0,
                address TEXT,
                city_id INTEGER,
                city_name TEXT,
                area_id INTEGER,
                area_name TEXT,
                source TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ''')
        
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
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (listing_id) REFERENCES listings (id)
            )
        ''')
        
        # Create media table
        cursor.execute('''
            CREATE TABLE IF NOT EXISTS media (
                id INTEGER PRIMARY KEY,
                file TEXT,
                url TEXT,
                listing_id INTEGER,
                space_id INTEGER,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (listing_id) REFERENCES listings (id),
                FOREIGN KEY (space_id) REFERENCES spaces (id)
            )
        ''')
        
        # Create indexes for better performance
        cursor.execute('CREATE INDEX IF NOT EXISTS idx_listings_city ON listings(city_id)')
        cursor.execute('CREATE INDEX IF NOT EXISTS idx_listings_source ON listings(source)')
        cursor.execute('CREATE INDEX IF NOT EXISTS idx_spaces_listing ON spaces(listing_id)')
        cursor.execute('CREATE INDEX IF NOT EXISTS idx_spaces_type ON spaces(type)')
        cursor.execute('CREATE INDEX IF NOT EXISTS idx_media_listing ON media(listing_id)')
        cursor.execute('CREATE INDEX IF NOT EXISTS idx_media_space ON media(space_id)')
        
        conn.commit()
        conn.close()
        logger.info("Database initialized successfully")
    
    def fetch_all_pages(self, url: str, params: Dict = None) -> List[Dict]:
        """
        Fetch all pages from API endpoint
        
        Args:
            url: API endpoint URL
            params: Additional parameters
            
        Returns:
            List of all data from all pages
        """
        all_data = []
        page = 1
        max_page = 1
        
        if params is None:
            params = {}
        
        while page <= max_page:
            try:
                params['page'] = page
                logger.info(f"Fetching page {page} from {url}")
                
                response = self.session.get(url, params=params, timeout=30)
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
                
                logger.info(f"Fetched {len(page_data)} items from page {page}")
                
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
        
        logger.info(f"Total items fetched: {len(all_data)}")
        return all_data
    
    def save_listings_data(self, listings_data: List[Dict]):
        """Save listings data to database"""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        saved_count = 0
        
        for item in listings_data:
            try:
                listing = item.get('listing', {})
                city = item.get('city', {})
                area = item.get('area', {})
                media_list = item.get('media', [])
                
                # Insert or update listing
                cursor.execute('''
                    INSERT OR REPLACE INTO listings 
                    (id, name, slug, spaces, address, city_id, city_name, area_id, area_name, source, updated_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ''', (
                    listing.get('id'),
                    listing.get('name'),
                    listing.get('slug'),
                    listing.get('spaces', 0),
                    listing.get('address'),
                    city.get('id'),
                    city.get('name'),
                    area.get('id'),
                    area.get('name'),
                    item.get('source'),
                    datetime.now().isoformat()
                ))
                
                listing_id = listing.get('id')
                
                # Save media for this listing
                for media in media_list:
                    cursor.execute('''
                        INSERT OR REPLACE INTO media 
                        (id, file, url, listing_id)
                        VALUES (?, ?, ?, ?)
                    ''', (
                        media.get('id'),
                        media.get('file'),
                        media.get('url'),
                        listing_id
                    ))
                
                saved_count += 1
                
            except Exception as e:
                logger.error(f"Error saving listing {item.get('listing', {}).get('id', 'unknown')}: {e}")
        
        conn.commit()
        conn.close()
        logger.info(f"Saved {saved_count} listings to database")
    
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
        """Fetch all data from both APIs and save to database"""
        logger.info("Starting data fetch process...")
        
        # Fetch listings data
        logger.info("Fetching listings data...")
        listings_data = self.fetch_all_pages(self.listings_url)
        self.save_listings_data(listings_data)
        
        # Fetch spaces data
        logger.info("Fetching spaces data...")
        spaces_data = self.fetch_all_pages(self.spaces_url)
        self.save_spaces_data(spaces_data)
        
        logger.info("Data fetch process completed!")
    
    def get_database_stats(self):
        """Get statistics about the data in database"""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        # Get counts
        cursor.execute("SELECT COUNT(*) FROM listings")
        listings_count = cursor.fetchone()[0]
        
        cursor.execute("SELECT COUNT(*) FROM spaces")
        spaces_count = cursor.fetchone()[0]
        
        cursor.execute("SELECT COUNT(*) FROM media")
        media_count = cursor.fetchone()[0]
        
        # Get listings by source
        cursor.execute("SELECT source, COUNT(*) FROM listings GROUP BY source")
        listings_by_source = cursor.fetchall()
        
        # Get spaces by type
        cursor.execute("SELECT type, COUNT(*) FROM spaces GROUP BY type")
        spaces_by_type = cursor.fetchall()
        
        conn.close()
        
        stats = {
            'listings_count': listings_count,
            'spaces_count': spaces_count,
            'media_count': media_count,
            'listings_by_source': dict(listings_by_source),
            'spaces_by_type': dict(spaces_by_type)
        }
        
        return stats
    
    def export_to_json(self, output_file: str = "asets_data_export.json"):
        """Export all data to JSON file"""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        # Get all listings with their media
        cursor.execute('''
            SELECT l.*, GROUP_CONCAT(m.id || '|' || m.file || '|' || m.url, '||') as media
            FROM listings l
            LEFT JOIN media m ON l.id = m.listing_id
            GROUP BY l.id
        ''')
        
        listings = []
        for row in cursor.fetchall():
            listing = {
                'id': row[0],
                'name': row[1],
                'slug': row[2],
                'spaces': row[3],
                'address': row[4],
                'city_id': row[5],
                'city_name': row[6],
                'area_id': row[7],
                'area_name': row[8],
                'source': row[9],
                'created_at': row[10],
                'updated_at': row[11],
                'media': []
            }
            
            if row[12]:  # media data
                for media_item in row[12].split('||'):
                    if media_item:
                        parts = media_item.split('|')
                        if len(parts) >= 3:
                            listing['media'].append({
                                'id': parts[0],
                                'file': parts[1],
                                'url': parts[2]
                            })
            
            listings.append(listing)
        
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
            'listings': listings,
            'spaces': spaces
        }
        
        # Save to JSON file
        with open(output_file, 'w', encoding='utf-8') as f:
            json.dump(export_data, f, indent=2, ensure_ascii=False)
        
        logger.info(f"Data exported to {output_file}")
        return export_data

def main():
    """Main function to run the data fetcher"""
    try:
        # Initialize fetcher
        fetcher = AsetsDataFetcher()
        
        # Fetch all data
        fetcher.fetch_all_data()
        
        # Get and display statistics
        stats = fetcher.get_database_stats()
        
        print("\n" + "="*50)
        print("DATA FETCH COMPLETED!")
        print("="*50)
        print(f"Total Listings: {stats['listings_count']}")
        print(f"Total Spaces: {stats['spaces_count']}")
        print(f"Total Media: {stats['media_count']}")
        print("\nListings by Source:")
        for source, count in stats['listings_by_source'].items():
            print(f"  {source}: {count}")
        print("\nSpaces by Type:")
        for space_type, count in stats['spaces_by_type'].items():
            print(f"  {space_type}: {count}")
        print("="*50)
        
        # Export to JSON
        fetcher.export_to_json()
        
    except Exception as e:
        logger.error(f"Error in main process: {e}")
        raise

if __name__ == "__main__":
    main()

