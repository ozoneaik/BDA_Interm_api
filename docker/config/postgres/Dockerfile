FROM postgres:14

RUN apt-get update \
    && apt-get install postgis postgresql-14-postgis-3 -y

# Copy in the load-extensions script
COPY ./load-extensions.sh /docker-entrypoint-initdb.d/
