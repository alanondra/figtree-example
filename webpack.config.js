const path = require('path');
const env = process.env.NODE_ENV;

const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const RemovePlugin = require('remove-files-webpack-plugin');

let resourcePath = './resources';
let publicPath = path.resolve(__dirname, 'public');
let buildPath = path.resolve(publicPath, 'assets', 'build');
let publicPrefix = '/assets/build/';

let resource = function (res) {
	return path.resolve(resourcePath, res);
};

let entries = {
	css: {
		'app': resource('sass/app.scss')
	},
	js: {
		'app': resource('js/app.js'),
		'vendor': resource('js/vendor.js')
	}
};

module.exports = [
	{
		entry: entries.css,

		output: {
			path: buildPath,

			publicPath: publicPrefix,

			// https://webpack.js.org/configuration/output/#outputfilename
			filename: ((env == 'production')
				? 'css/[name].[contentHash].css.js'
				: 'css/[name].css.js'
			)
		},

		plugins: [
			new MiniCssExtractPlugin({
				filename: ((env == 'production')
					? 'css/[name].[contentHash].css'
					: 'css/[name].css'
				)
			}),

			new RemovePlugin({
				after: {
					test: [
						{
							folder: buildPath,
							recursive: true,

							method: (path) => {
								return new RegExp(/\.css\.js$/, 'm').test(path);
							}
						}
					]
				}
			})
		],

		module: {
			rules: [
				{
					test: /\.css$/i,
					use: [
						MiniCssExtractPlugin.loader,
						{
							loader: 'css-loader',
							options: {
								sourceMap: false
							}
						}
					]
				},
				{
					test: /\.s(a|c)ss$/,
					use: [
						MiniCssExtractPlugin.loader,
						{
							loader: 'css-loader',
							options: {
								sourceMap: false
							}
						},
						{
							loader: 'resolve-url-loader',
							options: {
								keepQuery: true
							}
						},
						{
							loader: 'sass-loader',
							options: {
								sourceMap: true,
								additionalData: `$env: ${env};`
							}
						}
					],
				},
				{
					test: /\.(gif|jpe?g|png)$/i,
					type: 'asset/resource',
					generator: {
						filename: 'img/[name][ext][query]'
					}
				},
				{
					test: /\.(eot|otf|svg|ttf|woff2?)$/i,
					type: 'asset/resource',
					generator: {
						filename: 'fonts/[name][ext][query]'
					}
				}
			]
		}
	},
	{
		entry: entries.js,

		devtool: (env == 'production')
			? 'source-map'
			: 'inline-source-map',

		plugins: [
			//
		],

		output: {
			path: buildPath,

			publicPath: publicPrefix,

			filename: ((env == 'production')
				? 'js/[name].[contentHash].js'
				: 'js/[name].js'
			)
		}
	}
];