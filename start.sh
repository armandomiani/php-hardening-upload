#!/bin/bash
docker run -p 5000:5000 -v $(pwd)/src:/app --rm armandomiani//php-upload
