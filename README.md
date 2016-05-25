# docker-wsdl2phpgenerator

## Usage

Display available parameters :

```bash
docker run --rm -v `pwd`/output:/output stersin/wsdl2phpgenerator
```

Generate classes in ./output directory :

```bash
docker run --rm -v $(pwd)/output:/output stersin/wsdl2phpgenerator -w https://webservices.amazon.com/AWSECommerceService/AWSECommerceService.wsdl
```
