const path = require('path');
const htmlWebpackPlugin = require('html-webpack-plugin');
const copyWebpackPlugin = require('copy-webpack-plugin');
const webpack = require('webpack');

module.exports = {
  mode: 'development',
  entry: {
    app: './src/app.js',
  },
  devtool: 'inline-source-map',
  devServer: {
    contentBase: path.join(__dirname, 'build'),
    compress: true,
    port: 22031,
    historyApiFallback: true,
  },
  output: {
    path: path.resolve(__dirname, 'build'),
    filename: '[name].[contenthash].js',
  },
  plugins: [
    new htmlWebpackPlugin(
      {
        template: './static/index.html',
        publicPath: '',
      }),
    new webpack.DefinePlugin({
      API_ENDPOINT: JSON.stringify('/'),
    }),
  ],
  module: {
    rules: [
      {
        test: /\.m?js$/,
        exclude: /(node_modules|bower_components)/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-env'],
          },
        },
      },
      {
        test: /\.less$/,
        use: ['style-loader', 'css-loader', 'less-loader'],
      },
      {
        test: /\.css$/i,
        use: ['style-loader', 'css-loader'],
      },
      // this rule handles images
      {
        test: /\.jpe?g$|\.gif$|\.ico$|\.png$|\.svg$/,
        use: 'file-loader?name=[name].[ext]?[hash]',
      },
      
      // the following 3 rules handle font extraction
      {
        test: /\.woff(2)?(\?v=[0-9]\.[0-9]\.[0-9])?$/,
        loader: 'url-loader',
        options: {
          limit: 10000,
          mimetype: 'application/font-woff',
        },
      },
      
      {
        test: /\.(ttf|eot)(\?v=[0-9]\.[0-9]\.[0-9])?$/,
        loader: 'file-loader',
      },
      {
        test: /\.otf(\?.*)?$/,
        loader: 'file-loader',
        options: {
          name: '/fonts/[name].[ext]',
          mimetype: 'application/font-otf',
        },
      },
      {
        test: /\.html$/i,
        loader: 'html-loader',
        options: {
          esModule: false,
        },
      },
    ],
  },
};
