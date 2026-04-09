@extends('layouts.app')
@section('title', 'Mini Compiler')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Mini Compiler</h1>
    <p class="text-gray-500 mt-1 text-sm">Tulis dan jalankan pseudocode atau Python sederhana langsung di browser.</p>
</div>

<div class="grid lg:grid-cols-2 gap-5">
    {{-- Editor --}}
    <div class="card overflow-hidden">
        <div class="bg-gray-800 px-4 py-2.5 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="h-3 w-3 rounded-full bg-red-500"></span>
                <span class="h-3 w-3 rounded-full bg-yellow-500"></span>
                <span class="h-3 w-3 rounded-full bg-green-500"></span>
                <span class="text-gray-400 text-xs ml-3">pseudocode.py</span>
            </div>
            <div class="flex gap-2">
                <button onclick="clearEditor()" class="text-xs text-gray-400 hover:text-white px-2 py-1 rounded hover:bg-gray-700">Clear</button>
                <button onclick="runCode()" class="text-xs bg-indigo-500 hover:bg-indigo-400 text-white px-3 py-1 rounded font-medium">▶ Jalankan</button>
            </div>
        </div>
        <textarea id="code-editor" rows="20"
            class="w-full bg-gray-900 text-green-400 font-mono text-sm p-5 resize-none focus:outline-none border-0"
            spellcheck="false"
            placeholder="Tulis pseudocode atau Python di sini...

Contoh:
# Cek bilangan prima
n = int(input('Masukkan bilangan: '))
prima = True
if n < 2:
    prima = False
else:
    for i in range(2, int(n**0.5)+1):
        if n % i == 0:
            prima = False
            break

if prima:
    print(f'{n} adalah bilangan prima')
else:
    print(f'{n} bukan bilangan prima')
"></textarea>
    </div>

    {{-- Output + Tools --}}
    <div class="space-y-4">
        {{-- Output --}}
        <div class="card overflow-hidden">
            <div class="bg-gray-800 px-4 py-2.5 flex items-center justify-between">
                <span class="text-gray-400 text-xs">Output</span>
                <button onclick="clearOutput()" class="text-xs text-gray-400 hover:text-white">Clear</button>
            </div>
            <pre id="output-panel" class="bg-gray-900 text-gray-300 font-mono text-sm p-5 min-h-[200px] whitespace-pre-wrap">Klik "Jalankan" untuk melihat output di sini...</pre>
        </div>

        {{-- Template --}}
        <div class="card p-5">
            <h3 class="font-semibold text-gray-800 text-sm mb-3">📋 Template Cepat</h3>
            <div class="grid grid-cols-2 gap-2">
                <button onclick="loadTemplate('prima')" class="text-xs btn-outline py-1.5">Bilangan Prima</button>
                <button onclick="loadTemplate('factorial')" class="text-xs btn-outline py-1.5">Faktorial</button>
                <button onclick="loadTemplate('bubble')" class="text-xs btn-outline py-1.5">Bubble Sort</button>
                <button onclick="loadTemplate('fibonacci')" class="text-xs btn-outline py-1.5">Fibonacci</button>
                <button onclick="loadTemplate('palindrome')" class="text-xs btn-outline py-1.5">Palindrome</button>
                <button onclick="loadTemplate('bmi')" class="text-xs btn-outline py-1.5">Kalkulator BMI</button>
            </div>
        </div>

        {{-- Catatan --}}
        <div class="card p-5 bg-amber-50 border-amber-200">
            <p class="text-xs text-amber-700">
                ⚠ Mini compiler ini berjalan di browser menggunakan Skulpt (Python subset).
                Beberapa library Python lanjutan tidak tersedia. Cocok untuk latihan logika dasar.
            </p>
        </div>
    </div>
</div>

{{-- Skulpt (Python di browser) --}}
<script src="https://skulpt.org/js/skulpt.min.js"></script>
<script src="https://skulpt.org/js/skulpt-stdlib.js"></script>

@push('scripts')
<script>
const templates = {
    prima: `n = int(input("Masukkan bilangan: "))
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

    factorial: `def faktorial(n):
    if n <= 1:
        return 1
    return n * faktorial(n-1)

n = int(input("Masukkan bilangan: "))
print(f"{n}! = {faktorial(n)}")`,

    bubble: `arr = [64, 34, 25, 12, 22, 11, 90]
n = len(arr)
for i in range(n-1):
    for j in range(n-i-1):
        if arr[j] > arr[j+1]:
            arr[j], arr[j+1] = arr[j+1], arr[j]
print("Hasil pengurutan:")
print(arr)`,

    fibonacci: `n = int(input("Berapa deret Fibonacci? "))
a, b = 0, 1
for i in range(n):
    print(a, end=" ")
    a, b = b, a + b
print()`,

    palindrome: `kata = input("Masukkan kata: ")
if kata == kata[::-1]:
    print(f'"{kata}" adalah palindrome')
else:
    print(f'"{kata}" bukan palindrome')`,

    bmi: `berat = float(input("Berat badan (kg): "))
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
    print("Kategori: Obesitas")`
};

function loadTemplate(name) {
    document.getElementById('code-editor').value = templates[name] || '';
}

function clearEditor() {
    document.getElementById('code-editor').value = '';
}

function clearOutput() {
    document.getElementById('output-panel').textContent = 'Output dibersihkan.';
}

function runCode() {
    const code = document.getElementById('code-editor').value;
    const output = document.getElementById('output-panel');
    output.textContent = '';

    function outf(text) {
        output.textContent += text;
    }

    // Input handling sederhana: prompt
    function inf(prompt) {
        return window.prompt(prompt) || '';
    }

    Sk.configure({
        output: outf,
        read: (x) => {
            if (Sk.builtinFiles === undefined || Sk.builtinFiles["files"][x] === undefined)
                throw "File not found: '" + x + "'";
            return Sk.builtinFiles["files"][x];
        },
        inputfun: inf,
        inputfunTakesPrompt: true,
    });

    Sk.misceval.asyncToPromise(() =>
        Sk.importMainWithBody("<stdin>", false, code, true)
    ).then(() => {
        if (!output.textContent) output.textContent = '(Program selesai tanpa output)';
    }).catch(err => {
        output.textContent = '❌ Error:\n' + err.toString();
    });
}
</script>
@endpush
