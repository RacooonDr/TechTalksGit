module.exports = {
  apps: [{
    name: "tech-talks",
    script: 'server.js',
    instances: 1,
    exec_mode: "fork",
    watch: false, // Отключи вотч на продакшене чтобы не грузил сервер
    env: {
      NODE_ENV: "production",
      PORT: 3000,
      HOST: "localhost"
    },
    error_file: "./logs/err.log",
    out_file: "./logs/out.log",
    log_file: "./logs/combined.log",
    time: true
  }]
};