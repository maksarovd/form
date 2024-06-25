FROM node:lts-alpine

# Устанавливаем рабочую директорию
WORKDIR /app

# Копируем package.json и package-lock.json
COPY package*.json ./

# Устанавливаем зависимости
RUN npm install

# Копируем остальные файлы
COPY . .

# Сборка приложения
RUN npm run build

# Открываем порт 8080
EXPOSE 8080

# Запускаем сервер
CMD ["npm", "run", "serve"]