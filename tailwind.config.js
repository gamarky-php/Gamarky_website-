/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/views/**/*.blade.php",
    "./resources/js/**/*.js",
    "./resources/js/**/*.vue",
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
  ],
  safelist: [
    // Layout & Positioning
    "table-fixed","sticky","right-0","z-10","z-20","z-50","text-center","top-24","w-48",

    // Gradients
    "bg-gradient-to-l","from-[#1d4ed8]","to-[#7e22ce]",
    "from-[#667eea]","to-[#764ba2]",
    "from-[#0F2E5D]","to-[#1a4d8f]",
    "from-teal-500","to-teal-600","from-teal-600","to-teal-700",
    "bg-gradient-to-b","from-gray-50","to-white",

    // Status Colors
    "bg-black","text-white",
    "bg-red-100","text-red-800",
    "bg-orange-100","text-orange-800","bg-orange-50","text-orange-600","border-orange-300","text-orange-800",
    "bg-yellow-100","text-yellow-800","text-yellow-400",
    "bg-sky-100","text-sky-800",
    "bg-blue-100","text-blue-800","text-blue-100",
    "bg-green-100","text-green-800","text-green-600",
    "bg-teal-50","border-teal-500","text-teal-800","bg-teal-600","text-teal-600","bg-teal-100","border-2",
    "bg-purple-100","text-purple-800",

    // Custom widths
    "w-[220px]","min-w-[900px]",

    // Shipping Quote Colors
    "text-[#0F2E5D]","bg-[#0F2E5D]","border-[#0F2E5D]",
    
    // Dynamic patterns - Fixed to match actual usage
    { pattern: /(from|to|via)-\[\#([0-9a-fA-F]{3,8})\]/ },
    { pattern: /bg-(red|orange|yellow|green|blue|sky|purple|pink|gray|teal)-(50|100|200|300|400|500|600|700|800|900)/ },
    { pattern: /text-(red|orange|yellow|green|blue|sky|purple|pink|gray|white|black|teal)-(50|100|200|300|400|500|600|700|800|900)/ },
    { pattern: /border-(red|orange|yellow|green|blue|sky|purple|pink|gray|teal)-(50|100|200|300|400|500|600|700|800|900)/ },
  ],
  theme: { extend: {} },
  plugins: [],
};
