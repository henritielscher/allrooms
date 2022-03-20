const currentTask = process.env.npm_lifecycle_event;
const path = require("path");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const { CleanWebpackPlugin } = require("clean-webpack-plugin");

const babelConfig = {
  test: /\.js$/,
  exclude: /(node_modules)/,
  use: {
    loader: "babel-loader",
    options: {
      presets: [["@babel/preset-env", { targets: { node: "12" } }]],
    },
  },
};

const fileLoaderConfig = {
  test: /\.(png|jpe?g|gif|woff|ttf|svg)$/i,
  // type: "asset/resource",
  use: {
    loader: "file-loader",
    options: {
      outputPath: "assets/",
      name: "[name].[ext]",
      // publicPath: "/assets/",
    },
  },
};

let cssConfig = {
  test: /\.(s[ac]|c)ss$/i,
  use: ["css-loader", "postcss-loader", "sass-loader"],
};

let config = {
  entry: "./src/js/scripts.js",
  module: {
    rules: [cssConfig, fileLoaderConfig, babelConfig],
  },
  plugins: [],
};

if (currentTask == "build" || currentTask == "watch") {
  config.mode = "production";
  config.output = {
    path: path.resolve(__dirname, "public"),
    filename: "scripts.[contenthash].js",
  };
  cssConfig.use.unshift(MiniCssExtractPlugin.loader);
  config.plugins.push(
    new CleanWebpackPlugin(),
    new MiniCssExtractPlugin({ filename: "styles.[contenthash].css" })
  );
}

if (currentTask == "serve") {
  cssConfig.use.unshift("style-loader");
  config.devtool = "source-map";
  config.output = {
    filename: "bundled.js",
    publicPath: "http://localhost:3000",
  };
  config.devServer = {
    before: function (app, server) {
      // server._watch(["./**/*.php", "./**/*.js"]);
      server._watch(["./**/*.php", "!./functions.php"]);
    },
    public: "http://localhost:3000",
    publicPath: "http://localhost:3000/",
    disableHostCheck: true,
    contentBase: path.join(__dirname),
    contentBasePublicPath: "http://localhost:3000/",
    port: 3000,
    headers: {
      "Access-Control-Allow-Origin": "*",
    },
  };
  config.target = "web";
  config.mode = "development";
  fileLoaderConfig.use.options.publicPath = "http://localhost:3000/assets/";
}

module.exports = config;
