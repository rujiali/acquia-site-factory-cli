[![CircleCI](https://circleci.com/gh/rujiali/acquia-site-factory-cli/tree/master.svg?style=svg)](https://circleci.com/gh/rujiali/acquia-site-factory-cli/tree/master)
# Acquia Site Factory Cli

## Installation

Run ```composer install```

## Configuration

Put your 
1. Site factory username
2. Site factory apikey (You can find it in your profile page)
3. The site URL of your site factory UI (For example: https://www.govcms.acsitefactory.com)
4. The site ID (You can find in your site factory dashboard)

in ```sitefactory.yml``` file

## Usage

### Ping site factory
```./bin/AcquiaSiteFactoryCli app:ping```
### List all backups
```./bin/AcquiaSiteFactoryCli app:listBackups```
### List all sites
```bin/AcquiaSiteFactoryCli app:listSites```
### Get site details
```bin/AcquiaSiteFactoryCli app:getSiteDetails {site ID}```
### Create Backup
```./bin/AcquiaSiteFactoryCli app:createBackup```
### Show latest backup URL
```./bin/AcquiaSiteFactoryCli app:getLatestBackupURL```

## To do
:beer:Add commands to cover the rest of the endpoints in [Acquia site factory API reference](https://www.demo.acquia-cc.com/api/v1) 

## Credit
This project is sponsored by Australian government Department of Health