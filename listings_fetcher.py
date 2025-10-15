#!/usr/bin/env python3
"""
Script untuk mengambil data LISTINGS dari API ASETS
URL: https://api.asets.id/api/listings
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
        logging.FileHandler('listings_fetch.log'),
        logging.StreamHandler()
    ]
)
logger = logging.getLogger(__name__)

class ListingsDataFetcher:
    def __init__(self, db_path: str = "listings_data.db"):
        """
        Initialize the listings data fetcher
        
        Args:
            db_path: Path to SQLite database file
        """
        self.db_path = db_path
        self.listings_url = "https://api.asets.id/api/listings"
        self.session = requests.Session()
        self.session.headers.update({
            'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        })
        
        # Initialize database
        self.init_database()
    
    def init_database(self):
        """Initialize SQLite database with listings tables"""
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
        
        # Create media table for listings
        cursor.execute('''
            CREATE TABLE IF NOT EXISTS media (
                id INTEGER PRIMARY KEY,
                file TEXT,
                url TEXT,
                listing_id INTEGER,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (listing_id) REFERENCES listings (id)
            )
        ''')
        
        # Create indexes for better performance
        cursor.execute('CREATE INDEX IF NOT EXISTS idx_listings_city ON listings(city_id)')
        cursor.execute('CREATE INDEX IF NOT EXISTS idx_listings_source ON listings(source)')
        cursor.execute('CREATE INDEX IF NOT EXISTS idx_media_listing ON media(listing_id)')
        
        conn.commit()
        conn.close()
        logger.info("Database initialized successfully")
    
    def fetch_all_pages(self, params: Dict = None) -> List[Dict]:
        """
        Fetch all pages from listings API endpoint
        
        Args:
            params: Additional parameters
            
        Returns:
            List of all listings data from all pages
        """
        all_data = []
        page = 1
        max_page = 1
        
        if params is None:
            params = {}
        
        while page <= max_page:
            try:
                params['page'] = page
                logger.info(f"Fetching page {page} from {self.listings_url}")
                
                response = self.session.get(self.listings_url, params=params, timeout=30)
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
                
                logger.info(f"Fetched {len(page_data)} listings from page {page}")
                
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
        
        logger.info(f"Total listings fetched: {len(all_data)}")
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
    
    def fetch_all_data(self):
        """Fetch all listings data from API and save to database"""
        logger.info("Starting listings data fetch process...")
        
        # Fetch listings data
        listings_data = self.fetch_all_pages()
        self.save_listings_data(listings_data)
        
        logger.info("Listings data fetch process completed!")
    
    def get_database_stats(self):
        """Get statistics about the listings data in database"""
        conn = sqlite3.connect(self.db_path)
        cursor = conn.cursor()
        
        # Get counts
        cursor.execute("SELECT COUNT(*) FROM listings")
        listings_count = cursor.fetchone()[0]
        
        cursor.execute("SELECT COUNT(*) FROM media")
        media_count = cursor.fetchone()[0]
        
        # Get listings by source
        cursor.execute("SELECT source, COUNT(*) FROM listings GROUP BY source")
        listings_by_source = cursor.fetchall()
        
        # Get listings by city
        cursor.execute("SELECT city_name, COUNT(*) FROM listings GROUP BY city_name ORDER BY COUNT(*) DESC LIMIT 10")
        listings_by_city = cursor.fetchall()
        
        conn.close()
        
        stats = {
            'listings_count': listings_count,
            'media_count': media_count,
            'listings_by_source': dict(listings_by_source),
            'listings_by_city': dict(listings_by_city)
        }
        
        return stats
    
    def export_to_json(self, output_file: str = "listings_export.json"):
        """Export all listings data to JSON file"""
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
        
        conn.close()
        
        # Create export data
        export_data = {
            'export_timestamp': datetime.now().isoformat(),
            'listings': listings
        }
        
        # Save to JSON file
        with open(output_file, 'w', encoding='utf-8') as f:
            json.dump(export_data, f, indent=2, ensure_ascii=False)
        
        logger.info(f"Listings data exported to {output_file}")
        return export_data

def main():
    """Main function to run the listings data fetcher"""
    try:
        # Initialize fetcher
        fetcher = ListingsDataFetcher()
        
        # Fetch all data
        fetcher.fetch_all_data()
        
        # Get and display statistics
        stats = fetcher.get_database_stats()
        
        print("\n" + "="*50)
        print("LISTINGS DATA FETCH COMPLETED!")
        print("="*50)
        print(f"Total Listings: {stats['listings_count']}")
        print(f"Total Media: {stats['media_count']}")
        print("\nListings by Source:")
        for source, count in stats['listings_by_source'].items():
            print(f"  {source}: {count}")
        print("\nTop Cities:")
        for city, count in stats['listings_by_city'].items():
            print(f"  {city}: {count}")
        print("="*50)
        
        # Export to JSON
        fetcher.export_to_json()
        
    except Exception as e:
        logger.error(f"Error in main process: {e}")
        raise

if __name__ == "__main__":
    main()






