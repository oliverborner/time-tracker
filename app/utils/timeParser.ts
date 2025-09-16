export function parseTimeInput(input: string): number {
    input = (input || '').trim().toLowerCase();
    const regex = /(?:(\d+)\s*h)?\s*(?:(\d+)\s*m)?/;
    const m = regex.exec(input);
    if (m && (m[1] || m[2])) {
      const h = parseInt(m[1]||'0',10);
      const mm = parseInt(m[2]||'0',10);
      const total = h + mm/60;
      return roundUpQuarter(total);
    }
    // decimal
    const normalized = input.replace(',', '.');
    const dec = parseFloat(normalized);
    if (!isNaN(dec)) return roundUpQuarter(dec);
    return 0;
  }
  
  export function roundUpQuarter(hours: number): number {
    return Math.ceil(hours * 4) / 4;
  }
  