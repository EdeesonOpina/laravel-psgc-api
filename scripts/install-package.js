/**
 * PSGC Package Installation Script
 * 
 * Automatically sets up PSGC data after package installation
 * 
 * Data Source: @jobuntux/psgc (https://www.npmjs.com/package/@jobuntux/psgc)
 * Official Source: Philippine Statistics Authority (PSA)
 * Developer: Edeeson Opina (https://edeesonopina.vercel.app/)
 */

import fs from 'fs';
import path from 'path';
import { execSync } from 'child_process';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

console.log('🚀 Setting up PSGC API Package...');

// Check if we're in a Laravel project
if (!fs.existsSync('artisan')) {
    console.log('❌ This script must be run from a Laravel project root directory');
    process.exit(1);
}

// Check if @jobuntux/psgc is installed
try {
    require.resolve('@jobuntux/psgc');
    console.log('✓ @jobuntux/psgc package found');
} catch (e) {
    console.log('📦 Installing @jobuntux/psgc package...');
    try {
        execSync('npm install @jobuntux/psgc', { stdio: 'inherit' });
        console.log('✓ @jobuntux/psgc package installed');
    } catch (error) {
        console.log('❌ Failed to install @jobuntux/psgc package');
        console.log('Please run: npm install @jobuntux/psgc');
        process.exit(1);
    }
}

// Create data directory
const dataDir = path.join(process.cwd(), 'data');
if (!fs.existsSync(dataDir)) {
    fs.mkdirSync(dataDir);
    console.log('✓ Created data directory');
}

// Copy conversion script
const scriptPath = path.join(__dirname, 'convert_psgc_to_csv.js');
const targetScriptPath = path.join(dataDir, 'convert_psgc_to_csv.js');
const vendorScriptPath = path.join(process.cwd(), 'vendor', 'edeesonopina', 'laravel-psgc-api', 'scripts', 'convert_psgc_to_csv.js');

// Try to copy from vendor directory first, then fallback to local
if (fs.existsSync(vendorScriptPath)) {
    fs.copyFileSync(vendorScriptPath, targetScriptPath);
    console.log('✓ Copied data conversion script from vendor');
} else if (fs.existsSync(scriptPath)) {
    fs.copyFileSync(scriptPath, targetScriptPath);
    console.log('✓ Copied data conversion script from local');
}

// Run data conversion
console.log('🔄 Converting PSGC data to CSV format...');
try {
    execSync(`node ${targetScriptPath}`, { stdio: 'inherit' });
    console.log('✓ PSGC data converted to CSV');
} catch (error) {
    console.log('❌ Failed to convert PSGC data');
    console.log('You can run the conversion manually: node data/convert_psgc_to_csv.js');
}

// Run migrations
console.log('🗄️ Running database migrations...');
try {
    execSync('php artisan migrate', { stdio: 'inherit' });
    console.log('✓ Database migrations completed');
} catch (error) {
    console.log('❌ Failed to run migrations');
    console.log('Please run: php artisan migrate');
}

// Import data
console.log('📥 Importing PSGC data...');
try {
    execSync('php artisan psgc:import --regions=data/regions.csv --provinces=data/provinces.csv --city_municipalities=data/city_municipalities.csv --barangays=data/barangays.csv', { stdio: 'inherit' });
    console.log('✓ PSGC data imported successfully');
} catch (error) {
    console.log('❌ Failed to import PSGC data');
    console.log('You can import manually: php artisan psgc:import --regions=data/regions.csv --provinces=data/provinces.csv --city_municipalities=data/city_municipalities.csv --barangays=data/barangays.csv');
}

console.log('\n🎉 PSGC API Package setup completed!');
console.log('\n📚 Next steps:');
console.log('1. Start your Laravel server: php artisan serve');
console.log('2. Test the API: curl http://localhost:8000/api/v1/regions');
console.log('3. Check the documentation: vendor/edeesonopina/laravel-psgc-api/PACKAGE_README.md');
console.log('\n🙏 Thank you for using PSGC API Package!');
console.log('Developed by: Edeeson Opina (https://edeesonopina.vercel.app/)');
