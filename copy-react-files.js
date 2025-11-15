const fs = require('fs-extra');
const path = require('path');

const sourceDir = path.join(__dirname, 'webgalery-react', 'src');
const destDir = path.join(__dirname, 'resources', 'js');

async function copyFiles() {
  try {
    // Copy all files from src to resources/js
    await fs.copy(sourceDir, destDir, { overwrite: true });
    
    // Copy public files
    await fs.copy(
      path.join(sourceDir, '..', 'public'),
      path.join(__dirname, 'public'),
      { overwrite: true }
    );

    console.log('✅ Successfully copied React files!');
    console.log('\nNext steps:');
    console.log('1. Run: npm run dev');
    console.log('2. In a new terminal, run: php artisan serve');
    console.log('3. Visit: http://localhost:8000');
    
  } catch (err) {
    console.error('Error copying files:', err);
  }
}

copyFiles();
