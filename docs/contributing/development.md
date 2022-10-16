# Development Environment

For development, you will need to install Docker and Docker Composer. You can find the installation instructions for
your operating system [here](https://docs.docker.com/install/).

## Starting the development environment

Pull the latest version of this repository and start the docker containers:

```bash
git clone https://github.com/piscibus/siklid-api.git
```

```bash
cd siklid-api
```

```bash
docker-compose up -d
```

## Stopping the development environment

```bash
docker-compose down
```

## Commands

All commands are supposed to be run from the root of the project inside the container.
