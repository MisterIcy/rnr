const path = require('path');

module.exports = {
  mode: 'development',
  entry: {
    app: './src/app.js'
  },
  devtool: 'inline-source-map',
  devServer: {
    contentBase: path.join(__dirname, 'build'),
    compress: true,
    port: 22031,
    historyApiFallback: true
  },
  output: {
    path: path.resolve(__dirname, 'build'),
    filename: '[name].[contenthash].js'
  },
  plugins: [
  
  ]
  
}
