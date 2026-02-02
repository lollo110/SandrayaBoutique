// sicurezza: controlliamo che tutto esista
if (
  !window.STITCH_FONT ||
  !window.STITCH_PALETTES ||
  !window.patternWidth ||
  typeof PatternStitches === "undefined"
) {
  console.error("Stitch: dipendenze mancanti");
} else {

  document.querySelectorAll(".stitch-title").forEach(canvas => {
    const ctx = canvas.getContext("2d");

    const text = (canvas.dataset.text || "").toUpperCase();
    const size = 10;
    const paletteName = canvas.dataset.palette || "rose";
    const palette = window.STITCH_PALETTES[paletteName];

    let patterns = [];

    function resize() {
      canvas.width = canvas.clientWidth;
      canvas.height = canvas.clientHeight || size * 6;
    }

    function build() {
      patterns = [];

      const letters = [...text]
        .map(l => window.STITCH_FONT[l])
        .filter(Boolean);

      if (!letters.length) return;

      const spacing = size * 0.8;

      const totalWidth =
        letters.reduce(
          (s, p) => s + window.patternWidth(p, size),
          0
        ) + spacing * (letters.length - 1);

      let x = (canvas.width - totalWidth) / 2;
      const y =
        (canvas.height -
          window.patternHeight(letters[0], size)) / 2;

      letters.forEach(p => {
        patterns.push(
          new PatternStitches(ctx,p, palette, x, y, size)
        );
        x += window.patternWidth(p, size) + spacing;
      });
    }

    resize();
    build();

    window.addEventListener("resize", () => {
      resize();
      build();
    });

    function animate() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      patterns.forEach(p => {
        p.update();
        p.draw();
      });
      requestAnimationFrame(animate);
    }

    animate();
  });
}
