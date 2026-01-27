// public/js/heic-worker.js
importScripts('/js/vendor/heic-to.iife.js'); // <-- lo dejamos listo para la solución final (ver punto 4)
// Si todavía NO tienes ese archivo, por ahora pon el CDN como lo tenías:
// importScripts('https://cdn.jsdelivr.net/npm/heic-to@1.3.0/dist/iife/heic-to.js');

function pickConverter() {
  return self.heicTo || self.HeicTo || null;
}

self.onmessage = async (e) => {
  const { id, buffer, name, type, quality } = e.data;

  try {
    const convert = pickConverter();
    if (!convert) throw new Error('heic-to no está disponible en el worker (no se encontró heicTo/HeicTo).');

    // Heic-to suele trabajar bien con ArrayBuffer o Uint8Array dependiendo del build.
    const input = buffer instanceof ArrayBuffer ? buffer : buffer.buffer;

    let out = await convert({
      buffer: input,              // <- forma más estándar
      type: 'image/jpeg',
      quality: quality ?? 0.92,
    });

    // Algunos builds devuelven array (por frames)
    if (Array.isArray(out)) out = out[0];

    // Si devuelve Blob
    const outBlob = out instanceof Blob ? out : new Blob([out], { type: 'image/jpeg' });

    const outBuffer = await outBlob.arrayBuffer();
    const base = (name || 'imagen').replace(/\.[^/.]+$/, '');
    const outName = `${base}.jpg`;

    self.postMessage({ id, ok: true, buffer: outBuffer, name: outName }, [outBuffer]);
  } catch (err) {
    self.postMessage({
      id,
      ok: false,
      error: String(err?.message || err),
      debug: {
        fileName: name,
        fileType: type,
        converter: !!(self.heicTo || self.HeicTo),
      }
    });
  }
};
