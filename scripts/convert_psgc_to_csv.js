import fs from 'fs';
import path from 'path';

/**
 * PSGC Data Conversion Script
 * 
 * Converts official PSGC JSON data from @jobuntux/psgc package to CSV format
 * for Laravel import command.
 * 
 * Data Source: @jobuntux/psgc (https://www.npmjs.com/package/@jobuntux/psgc)
 * Official Source: Philippine Statistics Authority (PSA)
 * Developer: Edeeson Opina (https://edeesonopina.vercel.app/)
 */

// Create data directory if it doesn't exist
const dataDir = path.join(process.cwd(), 'data');
if (!fs.existsSync(dataDir)) {
    fs.mkdirSync(dataDir);
}

const psgcDataDir = path.join(process.cwd(), 'node_modules', '@jobuntux', 'psgc', 'data', '2025-2Q');

console.log('Converting PSGC JSON data to CSV...');

// Convert regions
const regionsData = JSON.parse(fs.readFileSync(path.join(psgcDataDir, 'regions.json'), 'utf8'));
const regionsCsv = [
    'code,name,short_name,island_group,status',
    ...regionsData.map(r => `"${r.psgcCode}","${r.regionName}","${r.regCode || ''}","","active"`)
].join('\n');

fs.writeFileSync(path.join(dataDir, 'regions.csv'), regionsCsv);
console.log(`✓ Converted ${regionsData.length} regions`);

// Convert provinces
const provincesData = JSON.parse(fs.readFileSync(path.join(psgcDataDir, 'provinces.json'), 'utf8'));
const provincesCsv = [
    'code,name,region_code,old_name,status',
    ...provincesData.map(p => {
        // Find the full region PSGC code by matching the short regCode
        const region = regionsData.find(r => r.regCode === p.regCode);
        return `"${p.psgcCode}","${p.provName}","${region?.psgcCode || ''}","${p.provOldName || ''}","active"`;
    })
].join('\n');

fs.writeFileSync(path.join(dataDir, 'provinces.csv'), provincesCsv);
console.log(`✓ Converted ${provincesData.length} provinces`);

// Convert cities/municipalities
const citiesMunicipalitiesData = JSON.parse(fs.readFileSync(path.join(psgcDataDir, 'muncities.json'), 'utf8'));
const citiesMunicipalitiesCsv = [
    'code,name,province_code,region_code,type,income_class,urban_rural,old_name,status',
    ...citiesMunicipalitiesData.map(cm => {
        // Find the full region PSGC code by matching the short regCode
        const region = regionsData.find(r => r.regCode === cm.regCode);
        // Find the full province PSGC code by matching the short provCode
        const province = provincesData.find(p => p.provCode === cm.provCode);
        return `"${cm.psgcCode}","${cm.munCityName}","${province?.psgcCode || ''}","${region?.psgcCode || ''}","${cm.cityClass === 'HUC' ? 'City' : 'Municipality'}","","","${cm.munCityOldName || ''}","active"`;
    })
].join('\n');

fs.writeFileSync(path.join(dataDir, 'city_municipalities.csv'), citiesMunicipalitiesCsv);
console.log(`✓ Converted ${citiesMunicipalitiesData.length} cities/municipalities`);

// Convert barangays
const barangaysData = JSON.parse(fs.readFileSync(path.join(psgcDataDir, 'barangays.json'), 'utf8'));
const barangaysCsv = [
    'code,name,city_municipality_code,province_code,region_code,old_name,status',
    ...barangaysData.map(b => {
        // Find the full region PSGC code by matching the short regCode
        const region = regionsData.find(r => r.regCode === b.regCode);
        // Find the full province PSGC code by matching the short provCode
        const province = provincesData.find(p => p.provCode === b.provCode);
        // Find the full city/municipality PSGC code by matching the short munCityCode
        const cityMunicipality = citiesMunicipalitiesData.find(cm => cm.munCityCode === b.munCityCode);
        return `"${b.psgcCode}","${b.brgyName}","${cityMunicipality?.psgcCode || ''}","${province?.psgcCode || ''}","${region?.psgcCode || ''}","${b.brgyOldName || ''}","active"`;
    })
].join('\n');

fs.writeFileSync(path.join(dataDir, 'barangays.csv'), barangaysCsv);
console.log(`✓ Converted ${barangaysData.length} barangays`);

console.log('\n✅ All PSGC data converted to CSV format in data/ directory');
console.log('Data source: Official PSA PSGC 2025 2Q');
console.log('\nTo import the data, run:');
console.log('php artisan psgc:import --regions=data/regions.csv --provinces=data/provinces.csv --city_municipalities=data/city_municipalities.csv --barangays=data/barangays.csv');

