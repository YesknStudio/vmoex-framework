# UPGRADE FROM 2.X to 3.0

此文档用于提供向2.x升级到3.0的向导。


> 以下文档中 `webuser`均指运行的web server的用户，如果不用`sudo -u <webuser>`执行命令的话，
> 会导致某些runtime目录的权限不对，导致网页打不开。
> 
> 在vmoex的根目录下，执行`php bin/console` 命令时建议都带上`sudo -u <webuser>` 前缀

## 清理缓存

任何时候，都应当清理缓存：

```bash
sudo -u <webuser> php bin/console cache:clear --env=prod
```

> 大多数情况下，如果你遇到了问题，不妨执行以上命令。

## 拉取代码

如果你修改或者增加过翻译词条，请直接看如下的**翻译文件**，如果没有，直接执行如下命令即可：

```
git pull origin master
```

## 翻译文件

> 如果你修改过翻译文件，用 `git statsu`命令会发现 `app/Resources/translations`目录下有修改的文件，这时`git pull`可能会失败，
这时，需要使用 `git stash`命令暂存起来，再执行`git pull origin master`，然后执行`git stash pop`将暂存的修改拿出来合并到新增的词条中。

将新增的翻译载入到数据库中：

```
sudo -u <webuser> php bin/console translation:persist
```

## 前端更新

由于bower已经不再维护了，因此vmoex也由bower切换到目前比较流行的前端包管理工具**yarn**，安装yarn：

```
npm install yarn -g
```

yarn的使用方式也类似：

```bash
yarn install
```

即可。

## 生成资源文件

```
sudo -u <webuser> php bin/console assetic:dump --env=prod
```


## 数据库结构更新

由于vmoex使用了redis的查询缓存，因此需要先执行如下命令去掉缓存：

```bash
sudo -u <webuser>  php bin/console doctrine:cache:clear-metadata --env=prod
```

再更新数据库结构：

```bash
php bin/console doctrine:schema:update --dump-sql --force --env=prod
```

以上命令在更新数据库的同时也会显示执行了哪些sql。


