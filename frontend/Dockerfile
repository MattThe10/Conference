FROM node:lts-alpine

WORKDIR /app

COPY package.json ./
COPY package-lock.json ./

RUN npm install

COPY . ./

RUN chown -R node:node /app/node_modules

CMD ["npm", "run", "serve"]