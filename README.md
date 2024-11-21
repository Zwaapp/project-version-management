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
   git clone https://github.com/your-username/dependency-insight-tool.git
   cd dependency-insight-tool
   composer install
   GITHUB_TOKEN=your_github_token
   BITBUCKET_TOKEN=your_bitbucket_token
   ```
2. Run

## TODO's
- Check main/master branch instead of only master
- Add support for GitLab repositories
- Add support for other dependency managers (e.g., npm, pip)
- Improve error handling and messaging
- Build in jobs system for fetching all data to prevent timeouts or api rate limits
- Add more tests
- Add more documentation
