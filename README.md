# RPC Module for Phwoolcon

## Brief
Uses [Hprose](https://github.com/hprose) (More specifically, [Hprose Swoole](https://github.com/hprose/hprose-swoole))

### Install Hprose extension
```bash
pecl install hprose
echo 'extension=hprose.so' > /etc/php/7.0/mods-available/hprose.ini
ln -s /etc/php/7.0/mods-available/hprose.ini /etc/php/7.0/cli/conf.d/20-hprose.ini
php --ri hprose
```
