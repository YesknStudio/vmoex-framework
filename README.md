<div style="text-align:center">
    <h1>Vmoex - 期望成为最美好的二次元社区</h1>
</div>

vmoex是一个开源的二次元社区程序。


## 疗效 

[戳我见效果](https://www.vmoex.com/)

## 安装指南

    cd /path/to/webroot/path
    
    git clone git@github.com:yeskn-studio/vmoex-framework.git

**或者指定版本，比如：**

    git clone --branch v2.1.1 git@github.com:yeskn-studio/vmoex-framework.git
    
**切换目录**

    cd vmoex-framework

**修改runtime目录权限**

    chown -R [webuser] var

**修改app/config/parameter.yaml.dist**

    vim app/config/parameter.yaml.dist

**安装php依赖**

    composer install （期间会提示配置，检查无误可一路回车）

**安装前端依赖**

    bower install
    
**创建数据库**

    php bin/console doctrine:schema:create （如果你已经手动创建了数据库，可跳过）

**导入数据**

    php bin/console doctrine:database:init

**修改管理员密码**

    php bin/console change-password -u admin -p [password]
    
**启动websocket**

    php bin/push-service start -d

**服务器上运行（dev**

    php bin/console server:run 0.0.0.0:8000

**本地运行（dev）**

    php bin/console server:run 127.0.0.1:8000

**访问**

    http://[127.0.0.1或者服务器ip]:8000

## 部署在nginx上

在上面的安装指南中运行的是dev模式，适合开发时环境，如果在真是环境运行，请务必使用类似nginx的web server来运行。
