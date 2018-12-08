<div style="text-align:center">
    <h1>Vmoex - 期望成为最美好的二次元社区</h1>
</div>

vmoex是一个开源的二次元社区程序。


## 疗效 

[戳我见效果](https://www.vmoex.com/)

## 安装指南


```bash
cd /path/to/webroot/path
git clone git@github.com:yeskn-studio/vmoex-framework.git

cd vmoex-framework
chown -R [webuser] var

# 修改app/config/parameter.yaml.dist

composer install
bower install

php bin/console server:run
```
