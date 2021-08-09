// const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;
const VuetifyLoaderPlugin = require('vuetify-loader/lib/plugin')
module.exports = {

    configureWebpack: {

        plugins: [
            // new BundleAnalyzerPlugin(),
            new VuetifyLoaderPlugin({
                progressiveImages: true
              })]
    },

};
// module.exports = {
//     chainWebpack: config => {
//       config.plugin('VuetifyLoaderPlugin').tap(args => [{
//         progressiveImages: true
//       }])
//     }
//   }

