# Usamos Node 20 en su versión Alpine (súper ligera)
FROM node:20-alpine

# Directorio de trabajo
WORKDIR /app

# Copiamos package.json y package-lock.json e instalamos
COPY package*.json ./
RUN npm install

# Copiamos el resto del código
COPY . .

# Exponemos el puerto de Vite
EXPOSE 5173

# Comando para iniciar Vite accesible desde fuera del contenedor
CMD ["npm", "run", "dev", "--", "--host", "0.0.0.0"]