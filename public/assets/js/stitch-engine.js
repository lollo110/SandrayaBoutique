const SPEED = 0.08;
const isMobile = window.innerWidth < 920;
const LINE_SCALE = isMobile ? 1 : 2;
const STITCHES_PER_FRAME = isMobile ? 5 : 10;
const STITCH_SCALE = 0.45;
const STITCH_OVERLAP = 0.15;

class Cucito {
  constructor(ctx, x, y, size, color) {
    this.ctx = ctx;
    this.x = x;
    this.y = y;
    this.size = size;
    this.color = color;
    this.state = 0;
    this.progress = 0;
  }

  drawSegment(x1, y1, x2, y2, p) {
    const ctx = this.ctx;
    const curve = isMobile ? 0 : Math.sin(p * Math.PI);
    const midX = x1 + (x2 - x1) * p + curve * 1.2;
    const midY = y1 + (y2 - y1) * p + curve * 1.2;

    const width = (2 + Math.sin(p * Math.PI)) * LINE_SCALE;

    ctx.strokeStyle = "rgba(0,0,0,0.25)";
    ctx.lineWidth = width + 2;
    ctx.beginPath();
    ctx.moveTo(x1 + 1, y1 + 1);
    ctx.lineTo(midX + 1, midY + 1);
    ctx.stroke();

    ctx.strokeStyle = this.color;
    ctx.lineCap = "round";
    ctx.lineWidth = width;
    ctx.beginPath();
    ctx.moveTo(x1, y1);
    ctx.lineTo(midX, midY);
    ctx.stroke();
  }

 draw() {
  const s = this.size * STITCH_SCALE;

  // Offset negativo per far sbordare la X verso le celle vicine
  const x1 = this.x - this.size * STITCH_OVERLAP;
  const y1 = this.y - this.size * STITCH_OVERLAP;

  const x2 = x1 + s + this.size * 2 * STITCH_OVERLAP;
  const y2 = y1 + s + this.size * 2 * STITCH_OVERLAP;

  if (this.state === 1)
    this.drawSegment(x1, y1, x2, y2, this.progress);

  if (this.state >= 2)
    this.drawSegment(x1, y1, x2, y2, 1);

  if (this.state === 3)
    this.drawSegment(x2, y1, x1, y2, this.progress);

  if (this.state >= 4)
    this.drawSegment(x2, y1, x1, y2, 1);
}
  update() {
    if (this.state === 1 || this.state === 3) {
      this.progress += SPEED;
      if (this.progress >= 1) {
        this.progress = 0;
        this.state++;
      }
    }
  }
}

class PatternStitches {
  constructor(ctx,pattern, palette, x, y, size) {
    this.ctx = ctx;
    this.groups = {};
    this.states = {};

    pattern.forEach((row, ry) => {
      row.forEach((cell, rx) => {
        if (!cell) return;

        const stitch = new Cucito(
          ctx,
          x + rx * size,
          y + ry * size,
          size,
          palette[cell]
        );

        if (!this.groups[stitch.color]) {
          this.groups[stitch.color] = [];
          this.states[stitch.color] = 0;
        }

        this.groups[stitch.color].push(stitch);
      });
    });
  }

  update() {
    for (let i = 0; i < STITCHES_PER_FRAME; i++) {
      for (const c in this.groups) {
        const idx = this.states[c];
        const stitch = this.groups[c][idx];
        if (!stitch) continue;

        if (stitch.state === 0) stitch.state = 1;
        stitch.update();
        if (stitch.state === 2) stitch.state = 3;
        if (stitch.state === 4) this.states[c]++;
      }
    }
  }

  draw() {
    for (const c in this.groups) {
      this.groups[c].forEach(s => s.draw());
    }
  }
}

window.PatternStitches = PatternStitches;
window.Cucito = Cucito;