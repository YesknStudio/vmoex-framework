# 安装手册

    cd /path/to/webroot/path
    
    git clone git@github.com:yeskn-studio/vmoex-framework.git

**或者指定版本，比如：**

    git clone --branch v2.1.1 git@github.com:yeskn-studio/vmoex-framework.git
    
**切换目录**

    cd vmoex-framework

**修改runtime目录权限**

    chown -R [webuser] var （各类日志缓存存放目录）
    chown -R [webuser] web/avatar （用户头像）

**修改配置文件**

    vim app/config/parameters.yml.dist

**安装php依赖**

    composer install （期间会提示配置，检查无误可一路回车）

**安装前端依赖**

    bower install （root用户请运行：bower install --allow-root）
    
**创建数据库**

    php bin/console doctrine:database:create （如果你已经手动创建了数据库，可跳过）

**导入数据**

    php bin/console doctrine:database:init
    
**载入翻译数据**

    php bin/console translation:persist

**修改管理员密码**

    php bin/console change-password -u admin -p [password]
    
**清理缓存**

    chown -R [webuser] var （上面已经执行过，这里再执行一次）
    sudo -u [webuser] php bin/console cache:clear --env=dev
    
**创建静态资源文件**

    php bin/console assetic:dump --env=dev
    
**启动websocket**

    php bin/push-service.php start -d

**服务器上运行（dev）**

    php bin/console server:run 0.0.0.0:8000

**本地运行（dev）**

    php bin/console server:run 127.0.0.1:8000

提示：以上两种方式运行时，看板娘可能无法加载，请使用nginx来运行

**访问**

    http://[127.0.0.1或者服务器ip]:8000

## 部署在nginx上

在上面的安装指南中运行的是dev模式，适合开发时环境，如果在真实环境运行，请务必使用类似nginx的web server来运行，nginx配置示例：

```nginx
server {
    listen          80;
    server_name     www.vmoex.com;

    root            /var/www/vmoex-framework/web;
    index           app.php;

    if (!-e $request_filename) {
        rewrite  ^(.*)$  /?$1  last;
        break;
    }
    
    location ~ \.php$ {
        include        fastcgi_params;
        fastcgi_pass   127.0.0.1:9000;
        fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
        access_log     /var/log/nginx/access.log main;
    }
}
```

nginx配置好后，还无法直接访问网站，请执行如下操作：

**清理prod模式下的缓存**

    chown -R [webuser] var
    sudo -u [webuser] php bin/console cache:clear --env=prod
    
**生成prod模式下的静态资源文件**

    php bin/console assetic:dump --env=prod

## 配置文件

app/config/parameters.yml.dist并不是真正生效的配置文件，真正生效的是自动生成的app/config/parameters.yml，
需修改配置时请修改此文件，修改完后，需要重新清理缓存或者生成静态资源文件。
