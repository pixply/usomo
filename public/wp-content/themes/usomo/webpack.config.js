const path = require("path");
const BrowserSyncPlugin = require("browser-sync-webpack-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");

module.exports = {
  entry: ["./assets/src/js/index.js", "./assets/src/sass/styles.sass"],
  output: {
    filename: "./assets/dist/script.min.js",
    path: path.resolve(__dirname),
  },
  devtool: "source-map",
  performance: {
    maxAssetSize: 300000,
    maxEntrypointSize: 400000,
  },
  module: {
    rules: [
      {
        test: /\.(js)$/,
        exclude: /node_modules/,
        use: ["babel-loader"],
      },
      {
        test: /\.(sa|sc|c)ss$/,
        use: [
          MiniCssExtractPlugin.loader,
          {
            loader: "css-loader",
            options: {
              url: false,
            },
          },
          "postcss-loader",
          "sass-loader",
        ],
      },
    ],
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: "./assets/dist/style.min.css",
    }),
    new BrowserSyncPlugin(
      {
        files: ["./assets/dist/*.*", "./**/*.php"],
        host: "localhost",
        port: 3000,
        proxy: "http://localhost/",
        injectCss: true,
        browser: "google chrome",
      },
      { reload: false }
    ),
  ],
};
