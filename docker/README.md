# Javaria Project Dockerfile

The Javaria Project is a simple dashboard tool to show charts. 

This dockerfile builds out a container with a simple LAMP installation. 

To build and run, copy the dockerfile to an empty folder and use:
```sh
$ docker build -t javaria_project:v1.0 .
$ docker run -p 80:80 javaria_project:v1.0
```

To keep a persistent database, use this line:
```sh
$ docker run -p 80:80 -v D:\path\to\mysql\folder:/var/lib/mysql javariaproject:v1.0
```


Access the resulting container installation in a browser using:
```
http://localhost
```

Login username and password is 'admin'. 