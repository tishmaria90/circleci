#!/bin/bash

# Please, run this script from root of project

mkdir deployment/decrypted_credentials
openssl aes-256-cbc -K $encrypted_3a51e51f8e9f75c3_key -iv $encrypted_3a51e51f8e9f75c3_iv -in deployment/id_rsa_deployment.zip.enc -out deployment/decrypted_credentials/id_rsa_deployment.zip -d
unzip deployment/decrypted_credentials/id_rsa_deployment.zip -d deployment/decrypted_credentials
