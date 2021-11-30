# WhyJustRun Clubsite

Check out [whyjustrun.ca](https://whyjustrun.ca) for more information about this project.

## Usage

The Clubsite depends on [WhyJustRun Core](https://github.com/WhyJustRun/Core), so first run it.

After Core is running, to start the Clubsite:

1) Use the default configuration: `cp .env.web{.sample,}`
2) Start the containers: `docker-compose up --build`

You should be up and running. Head to [localhost:3001](http://localhost:3001).

### Console

To start the CakePHP console, run `docker-compose exec web /application/app/Console/cake -app /application/app`

### Production

For production use, set `WJR_CLUBSITE_ENV=production`

In production mode, the app uses minified assets. In order to build them, run `docker-compose exec web /application/app/Console/minify.sh`

## Closed Source Components

We use [Redactor](https://imperavi.com/redactor/) as a rich text editor. However the source code can't be bundled with the project due to licensing restrictions. To test rich text editing related functionality, place `redactor.min.js` and `redactor.css` inside `src/app/webroot/js/redactor`.
