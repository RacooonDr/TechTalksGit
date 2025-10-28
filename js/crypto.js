// crypto.js - Функции для работы с криптографией
console.log('Crypto.js loaded');

window.CryptoUtils = {
    generateKeys: async function() {
        try {
            console.log('Generating RSA keys...');
            const keyPair = await window.crypto.subtle.generateKey(
                {
                    name: "RSA-OAEP",
                    modulusLength: 2048,
                    publicExponent: new Uint8Array([1, 0, 1]),
                    hash: { name: "SHA-256" },
                },
                true,
                ["encrypt", "decrypt"]
            );
            console.log('RSA keys generated successfully');
            return keyPair;
        } catch (error) {
            console.error('Key generation error:', error);
            // Заглушка для тестирования
            return {
                publicKey: { kty: 'RSA', alg: 'RSA-OAEP-256' },
                privateKey: { kty: 'RSA', alg: 'RSA-OAEP-256' }
            };
        }
    },

    encryptMessage: async function(publicKey, message) {
        try {
            console.log('Encrypting message...');
            return message; // демо
        } catch (error) {
            console.error('Encryption error:', error);
            return message;
        }
    },

    decryptMessage: async function(privateKey, encryptedMessage) {
        try {
            console.log('Decrypting message...');
            return encryptedMessage; // демо
        } catch (error) {
            console.error('Decryption error:', error);
            return encryptedMessage;
        }
    },

    hashPassword: async function(password) {
        try {
            console.log('Hashing password...');
            const encoder = new TextEncoder();
            const data = encoder.encode(password);
            const hash = await window.crypto.subtle.digest('SHA-256', data);
            const hashArray = Array.from(new Uint8Array(hash));
            const hashHex = hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
            console.log('Password hashed successfully');
            return hashHex;
        } catch (error) {
            console.error('Hash error:', error);
            return password;
        }
    }
};

// Глобальные ссылки для совместимости со старым кодом
window.generateKeys = window.CryptoUtils.generateKeys;
window.encryptMessage = window.CryptoUtils.encryptMessage;
window.decryptMessage = window.CryptoUtils.decryptMessage;
window.hashPassword = window.CryptoUtils.hashPassword;

console.log('Crypto functions registered globally');
