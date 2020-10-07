# Generate a new JWT public and private key

```
openssl genrsa -out apps/api-gateway/jwt/certificate/private.pem -aes256 4096
openssl rsa -pubout -in apps/api-gateway/jwt/certificate/private.pem -out apps/api-gateway/jwt/certificate/public.pem
```

# Authenticating Docker to the Gitlab registry

[https://docs.gitlab.com/ee/user/packages/container_registry/#authenticating-to-the-gitlab-container-registry](https://docs.gitlab.com/ee/user/packages/container_registry/#authenticating-to-the-gitlab-container-registry)
