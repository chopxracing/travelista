FROM node:18-alpine
WORKDIR /var/www/html

# Копируем package файлы
COPY package*.json ./
COPY vite.config.js ./

# Устанавливаем зависимости
RUN npm install

# Команда по умолчанию
CMD ["npm", "run", "dev", "--", "--host", "0.0.0.0"]
