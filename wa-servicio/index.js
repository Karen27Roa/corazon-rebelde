const express = require('express');
const { Client, LocalAuth } = require('whatsapp-web.js');
const qrcode = require('qrcode-terminal');

const TOKEN = process.env.WA_TOKEN || 'cambia-este-token';
const app = express();
app.use(express.json());

let listo = false;
const client = new Client({
  authStrategy: new LocalAuth({ dataPath: 'C:\\wa-sesion' }),
  puppeteer: { args: ['--no-sandbox', '--disable-setuid-sandbox'] },
});

client.on('qr', qr => qrcode.generate(qr, { small: true }));
client.on('ready', () => { listo = true; console.log('WhatsApp listo'); });
client.on('disconnected', () => { listo = false; console.log('Desconectado'); });
client.initialize().catch(e => console.error('Error al iniciar:', e.message));

app.post('/enviar', async (req, res) => {
  if (req.headers['x-token'] !== TOKEN) return res.status(401).json({ error: 'no autorizado' });
  if (!listo) return res.status(503).json({ error: 'WhatsApp no conectado' });

  const { numero, mensaje } = req.body;
  try {
    const wid = await client.getNumberId(numero);   // valida que el número tenga WhatsApp
    if (!wid) return res.status(404).json({ error: 'número sin WhatsApp' });
    await client.sendMessage(wid._serialized, mensaje);
    res.json({ ok: true });
  } catch (e) {
    res.status(500).json({ error: e.message });
  }
});

app.listen(3000, '127.0.0.1', () => console.log('Servicio en :3000'));