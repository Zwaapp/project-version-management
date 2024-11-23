# Project version management

This tool provides an easy way to gain insights into the status of dependencies in your projects. By reading `composer.json` and `composer.lock` files from GitHub or Bitbucket repositories, the tool generates an overview of the projects, their dependencies, current versions, and any available updates.

With this information, you can quickly identify outdated dependencies and take action to keep your projects secure, performant, and up to date.

## Features

- Fetches `composer.json` and `composer.lock` files from GitHub and Bitbucket repositories.
- Analyzes dependencies and their versions.
- Highlights outdated dependencies and suggests updates.
- Generates a report for improved visibility across projects.

## Installation

1. Clone this repository:
   ```bash
   composer install
   npm install
   npm run dev
   ```
2. Setup your .env credentials
3. php artisan app:fetch_projects
4. Enjoy the show!

### Good to know:
- The tool uses the main branch selected at the version system. This can be edited after loading the initial data
- The tool uses the `composer.lock` file to determine the current version of the dependencies. If the file is not present, the tool skip the entire project and it will not be stored.
- A cron runs daily to fetch the latest data from the repositories.
- It is possible to manually trigger fetching new data
- I personally use ddev to setup my env's for testing, but you can use any other tool you like

## TODO's
- Add tests
- Add support for GitLab repositories
- Use login for making this data more secure
- Add more documentation

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
