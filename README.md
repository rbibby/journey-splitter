# Journey Splitter

**A demo of this project is currently available at [hackathon2020.ryanbibby.com]([hackathon2020.ryanbibby.com])**

The aim of this project is to allow you to plan long car journeys wityh suggestions of where to stop along the way. You are
able to enter a start location and a destination along with how often you want to stop. The website will figure out how many
breaks you should take, spread these out along your route and offer suggestions of places to visit.

## Made With

* Laravel 8
* Tailwind CSS
* Google Maps API

## Setting Up

* You will need an API key for Google Maps, more information on this can be found [here](https://developers.google.com/maps/gmp-get-started).
* Put your API key in the `.env` file under `GOOGLE_MAPS_KEY=`
* Run `composer install`
* Run `php artisan serve`
* THe website will now be running at `http://localhost:8000/`

## Contributing

Thank you for considering contributing to the project! Please feel free to submit pull requests which you think would
benefit the project. If you're thinking of spending a lot of time on a pull request it may be worth discussing this first
to check that the pull request matches the aims of the project, you can do this by opening an issue.

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Ryan Bibby via [hello@rbibby.co.uk](mailto:hello@rbibby.co.uk). All security vulnerabilities will be promptly addressed.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
