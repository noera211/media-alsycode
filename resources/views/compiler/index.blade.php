@extends('layouts.app')
@section('title', 'Mini Compiler')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Mini Compiler</h1>
    <p class="text-gray-500 mt-1 text-sm">Tulis dan jalankan kode Python atau JavaScript langsung di browser.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    {{-- Editor --}}
    <div class="card overflow-hidden min-w-0">
        <div class="bg-gray-800 px-4 py-2 flex items-center justify-between">
            <div class="flex items-center gap-1">
                <span class="h-3 w-3 rounded-full bg-red-500"></span>
                <span class="h-3 w-3 rounded-full bg-yellow-500"></span>
                <span class="h-3 w-3 rounded-full bg-green-500"></span>
                <div class="flex ml-4 gap-1">
                    <button id="tab-python" onclick="switchLang('python')"
                        class="lang-tab active-tab text-xs px-3 py-1 rounded font-medium transition-colors">
                        🐍 Python
                    </button>
                    <button id="tab-javascript" onclick="switchLang('javascript')"
                        class="lang-tab text-xs px-3 py-1 rounded font-medium transition-colors">
                        ⚡ JavaScript
                    </button>
                </div>
            </div>
            <div class="flex gap-2">
                <button onclick="clearEditor()" class="text-xs text-gray-400 hover:text-white px-2 py-1 rounded hover:bg-gray-700 transition-colors">Clear</button>
                <button onclick="runCode()" class="text-xs bg-indigo-500 hover:bg-indigo-400 text-white px-3 py-1 rounded font-medium transition-colors">▶ Jalankan</button>
            </div>
        </div>

        <div class="border-b border-gray-700 px-4 py-1.5" style="background:#1a1f2e;">
            <span id="file-label" class="text-gray-500 text-xs">main.py</span>
        </div>

        <textarea id="code-editor" rows="14"
            class="w-full bg-gray-900 text-green-400 font-mono text-sm p-4 sm:p-5 resize-none focus:outline-none border-0 leading-relaxed"
            style="min-height:280px;"
            spellcheck="false"
            placeholder="Tulis kode Python di sini..."></textarea>
    </div>

    {{-- Output + Tools --}}
    <div class="space-y-4">

        <div class="card overflow-hidden">
            <div class="bg-gray-800 px-4 py-2.5 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-gray-400 text-xs">Output</span>
                    <span id="run-status" class="hidden text-xs px-2 py-0.5 rounded-full font-medium"></span>
                </div>
                <button onclick="clearOutput()" class="text-xs text-gray-400 hover:text-white transition-colors">Clear</button>
            </div>
            <pre id="output-panel"
                 class="bg-gray-900 text-gray-300 font-mono text-sm p-5 min-h-[220px] whitespace-pre-wrap overflow-auto"
                 style="max-height:340px;">Klik "Jalankan" untuk melihat output di sini...</pre>
        </div>

        <div class="card p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold text-gray-800 text-sm">📋 Template Cepat</h3>
                <span id="template-lang-label" class="text-xs text-gray-400">Python</span>
            </div>
            <div id="templates-python" class="grid grid-cols-2 gap-2">
                <button onclick="loadTemplate('py_prima')"      class="text-xs btn-outline py-1.5">Bilangan Prima</button>
                <button onclick="loadTemplate('py_factorial')"  class="text-xs btn-outline py-1.5">Faktorial</button>
                <button onclick="loadTemplate('py_bubble')"     class="text-xs btn-outline py-1.5">Bubble Sort</button>
                <button onclick="loadTemplate('py_fibonacci')"  class="text-xs btn-outline py-1.5">Fibonacci</button>
                <button onclick="loadTemplate('py_palindrome')" class="text-xs btn-outline py-1.5">Palindrome</button>
                <button onclick="loadTemplate('py_bmi')"        class="text-xs btn-outline py-1.5">Kalkulator BMI</button>
            </div>
            <div id="templates-javascript" class="grid grid-cols-2 gap-2 hidden">
                <button onclick="loadTemplate('js_prima')"      class="text-xs btn-outline py-1.5">Bilangan Prima</button>
                <button onclick="loadTemplate('js_factorial')"  class="text-xs btn-outline py-1.5">Faktorial</button>
                <button onclick="loadTemplate('js_bubble')"     class="text-xs btn-outline py-1.5">Bubble Sort</button>
                <button onclick="loadTemplate('js_fibonacci')"  class="text-xs btn-outline py-1.5">Fibonacci</button>
                <button onclick="loadTemplate('js_palindrome')" class="text-xs btn-outline py-1.5">Palindrome</button>
                <button onclick="loadTemplate('js_closure')"    class="text-xs btn-outline py-1.5">Closure & Arrow</button>
            </div>
        </div>

        <div id="info-python" class="card p-4 bg-amber-50 border-amber-200">
            <p class="text-xs text-amber-700">
                ⚠ Python berjalan via <strong>Skulpt</strong> (subset Python 3 di browser).
                Beberapa library lanjutan tidak tersedia. Cocok untuk latihan logika dasar.
            </p>
        </div>
        <div id="info-javascript" class="card p-4 bg-blue-50 border-blue-200 hidden">
            <p class="text-xs text-blue-700">
                ⚡ JavaScript berjalan secara <strong>native</strong> di browser.
                <code>console.log()</code> dan sebagian besar ES6+ didukung penuh.
                <code>prompt()</code> tersedia untuk input.
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://skulpt.org/js/skulpt.min.js"></script>
<script src="https://skulpt.org/js/skulpt-stdlib.js"></script>

<style>
.lang-tab { color:#9ca3af; background:transparent; }
.lang-tab:hover { background:#374151; color:#e5e7eb; }
.active-tab { background:#4f46e5 !important; color:#ffffff !important; }
</style>

<script>
let currentLang = 'python';
const codeStore = { python: '', javascript: '' };

const templates = {
    py_prima: `n = int(input("Masukkan bilangan: "))
prima = True
if n < 2:
    prima = False
else:
    for i in range(2, int(n**0.5)+1):
        if n % i == 0:
            prima = False
            break
if prima:
    print(f"{n} adalah bilangan prima")
else:
    print(f"{n} bukan bilangan prima")`,

    py_factorial: `def faktorial(n):
    if n <= 1:
        return 1
    return n * faktorial(n-1)

n = int(input("Masukkan bilangan: "))
print(f"{n}! = {faktorial(n)}")`,

    py_bubble: `arr = [64, 34, 25, 12, 22, 11, 90]
n = len(arr)
for i in range(n-1):
    for j in range(n-i-1):
        if arr[j] > arr[j+1]:
            arr[j], arr[j+1] = arr[j+1], arr[j]
print("Hasil pengurutan:")
print(arr)`,

    py_fibonacci: `n = int(input("Berapa deret Fibonacci? "))
a, b = 0, 1
for i in range(n):
    print(a, end=" ")
    a, b = b, a + b
print()`,

    py_palindrome: `kata = input("Masukkan kata: ")
if kata == kata[::-1]:
    print(f'"{kata}" adalah palindrome')
else:
    print(f'"{kata}" bukan palindrome')`,

    py_bmi: `berat = float(input("Berat badan (kg): "))
tinggi = float(input("Tinggi badan (m): "))
bmi = berat / (tinggi ** 2)
print(f"BMI Anda: {bmi:.2f}")
if bmi < 18.5:
    print("Kategori: Kurus")
elif bmi < 25:
    print("Kategori: Normal")
elif bmi < 30:
    print("Kategori: Gemuk")
else:
    print("Kategori: Obesitas")`,

    js_prima: `function isPrima(n) {
    if (n < 2) return false;
    for (let i = 2; i <= Math.sqrt(n); i++) {
        if (n % i === 0) return false;
    }
    return true;
}
const n = parseInt(prompt("Masukkan bilangan:"));
console.log(n + (isPrima(n) ? " adalah" : " bukan") + " bilangan prima");`,

    js_factorial: `function faktorial(n) {
    if (n <= 1) return 1;
    return n * faktorial(n - 1);
}
const n = parseInt(prompt("Masukkan bilangan:"));
console.log(n + "! = " + faktorial(n));`,

    js_bubble: `let arr = [64, 34, 25, 12, 22, 11, 90];
for (let i = 0; i < arr.length - 1; i++) {
    for (let j = 0; j < arr.length - i - 1; j++) {
        if (arr[j] > arr[j+1]) [arr[j], arr[j+1]] = [arr[j+1], arr[j]];
    }
}
console.log("Hasil: " + arr.join(", "));`,

    js_fibonacci: `const n = parseInt(prompt("Berapa deret Fibonacci?"));
let a = 0, b = 1, hasil = [];
for (let i = 0; i < n; i++) { hasil.push(a); [a,b]=[b,a+b]; }
console.log(hasil.join(" "));`,

    js_palindrome: `const kata = prompt("Masukkan kata:");
const balik = kata.split("").reverse().join("");
console.log('"' + kata + '" ' + (kata===balik ? "adalah" : "bukan") + " palindrome");`,

    js_closure: `const buatCounter = (start = 0) => {
    let count = start;
    return {
        tambah: () => ++count,
        kurang: () => --count,
        reset:  () => { count = start; return count; },
        nilai:  () => count,
    };
};
const counter = buatCounter(10);
console.log("Nilai awal:", counter.nilai());
console.log("Tambah:", counter.tambah());
console.log("Tambah:", counter.tambah());
console.log("Kurang:", counter.kurang());
console.log("Reset:", counter.reset());

const angka = [1,2,3,4,5,6,7,8,9,10];
const genap   = angka.filter(x => x % 2 === 0);
const kuadrat = genap.map(x => x ** 2);
const total   = kuadrat.reduce((acc,x) => acc+x, 0);
console.log("Genap:", genap.join(", "));
console.log("Kuadrat:", kuadrat.join(", "));
console.log("Total:", total);`
};

function switchLang(lang) {
    codeStore[currentLang] = document.getElementById('code-editor').value;
    currentLang = lang;
    document.querySelectorAll('.lang-tab').forEach(t => t.classList.remove('active-tab'));
    document.getElementById('tab-' + lang).classList.add('active-tab');
    document.getElementById('file-label').textContent = lang === 'python' ? 'main.py' : 'main.js';
    const editor = document.getElementById('code-editor');
    editor.classList.remove('text-green-400', 'text-yellow-300');
    editor.classList.add(lang === 'python' ? 'text-green-400' : 'text-yellow-300');
    editor.value = codeStore[lang];
    editor.placeholder = lang === 'python' ? 'Tulis kode Python di sini...' : 'Tulis kode JavaScript di sini...';
    document.getElementById('templates-python').classList.toggle('hidden', lang !== 'python');
    document.getElementById('templates-javascript').classList.toggle('hidden', lang !== 'javascript');
    document.getElementById('info-python').classList.toggle('hidden', lang !== 'python');
    document.getElementById('info-javascript').classList.toggle('hidden', lang !== 'javascript');
    document.getElementById('template-lang-label').textContent = lang === 'python' ? 'Python' : 'JavaScript';
    clearOutput();
}

function loadTemplate(name) {
    const code = templates[name] || '';
    document.getElementById('code-editor').value = code;
    codeStore[currentLang] = code;
}

function clearEditor() {
    document.getElementById('code-editor').value = '';
    codeStore[currentLang] = '';
}

function clearOutput() {
    document.getElementById('output-panel').textContent = 'Output dibersihkan.';
    setStatus('');
}

function setStatus(type, text) {
    const el = document.getElementById('run-status');
    if (!type) { el.classList.add('hidden'); return; }
    el.classList.remove('hidden');
    el.textContent = text;
    el.className = 'text-xs px-2 py-0.5 rounded-full font-medium ' +
        (type === 'ok'  ? 'bg-emerald-100 text-emerald-700' :
         type === 'err' ? 'bg-red-100 text-red-600' : 'bg-indigo-100 text-indigo-700');
}

function runCode() {
    currentLang === 'python' ? runPython() : runJavaScript();
}

function runPython() {
    const code   = document.getElementById('code-editor').value.trim();
    const output = document.getElementById('output-panel');
    if (!code) { output.textContent = '(Editor kosong)'; return; }
    output.textContent = '';
    setStatus('info', '⏳ Berjalan...');
    Sk.configure({
        output: t => { output.textContent += t; },
        read: x => {
            if (Sk.builtinFiles?.files[x] === undefined) throw "File not found: '" + x + "'";
            return Sk.builtinFiles.files[x];
        },
        inputfun: p => window.prompt(p) ?? '',
        inputfunTakesPrompt: true,
    });
    Sk.misceval.asyncToPromise(() =>
        Sk.importMainWithBody('<stdin>', false, code, true)
    ).then(() => {
        if (!output.textContent) output.textContent = '(Program selesai tanpa output)';
        setStatus('ok', '✓ Selesai');
    }).catch(err => {
        output.textContent = '❌ Error:\n' + err.toString();
        setStatus('err', '✗ Error');
    });
}

function runJavaScript() {
    const code   = document.getElementById('code-editor').value.trim();
    const output = document.getElementById('output-panel');
    if (!code) { output.textContent = '(Editor kosong)'; return; }
    output.textContent = '';
    setStatus('info', '⏳ Berjalan...');
    const lines = [];
    const orig = { log: console.log, warn: console.warn, error: console.error, info: console.info };
    const cap = pre => (...args) => lines.push(pre + args.map(a =>
        a === null ? 'null' : a === undefined ? 'undefined' :
        typeof a === 'object' ? JSON.stringify(a, null, 2) : String(a)
    ).join(' '));
    console.log = cap(''); console.warn = cap('⚠ '); console.error = cap('✗ '); console.info = cap('ℹ ');
    try {
        new Function(code)();
        output.textContent = lines.length ? lines.join('\n') : '(Program selesai tanpa output)';
        setStatus('ok', '✓ Selesai');
    } catch (err) {
        output.textContent = (lines.length ? lines.join('\n') + '\n\n' : '') +
            '❌ ' + err.constructor.name + ': ' + err.message;
        setStatus('err', '✗ Error');
    } finally {
        Object.assign(console, orig);
    }
}

document.addEventListener('DOMContentLoaded', () => loadTemplate('py_prima'));
</script>
@endpush