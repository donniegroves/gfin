## mysql configuration path
/etc/mysql/mysql.conf.d/mysqld.cnf

## On AWS, storage folder has to have www-data:www-data permissions
# Install Docker on WSL2 Ubuntu 22.04
```
sudo apt update
sudo apt install ca-certificates curl gnupg lsb-release
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg
echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
sudo apt update
sudo apt install docker-ce docker-ce-cli containerd.io
sudo update-alternatives --config iptables (EDIT: enter 1)
```

# Starting MySQL container
`docker-compose up`

# Starting dev server
`php artisan serve`

# Starting vue
`npm run hot`

# Problems with loading vue
Referred to https://www.webdesignvista.com/vue-hot-reload-with-laravel-mix/

# Problems with displays not changing:
```bash
sudo service mysql stop
php artisan down
npm run build
sudo service mysql start
php artisan up
sudo chown -R www-data:www-data storage/
```

# To Fix permission issues in Production:
```bash
sudo chown -R www-data:www-data /var/www/gfin/storage
sudo chown -R ubuntu:ubuntu /var/www/gfin/storage/app/
```
