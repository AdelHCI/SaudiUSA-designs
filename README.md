# SaudiUSA-designs

## Project setup
```
npm install
```

### Compiles and hot-reloads for development
```
npm run serve
```

### Compiles and minifies for production
```
npm run build
```

### Deploys to your server based on ftp input
```
npm run deploy
```
This requires that you edit the env.local file with your ftp info and rename it to .env.local

You can change what it deploys by editing config.js under the ftpdeploy directory

Check: https://www.npmjs.com/package/vue-cli-plugin-ftp-deploy for more info 

### Builds and deploys to your server based on ftp input
```
npm run bluiddeploy
```

### Lints and fixes files
```
npm run lint
```

### Customize configuration
See [Configuration Reference](https://cli.vuejs.org/config/).
