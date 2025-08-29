<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>React CDN Example</title>
  <!-- React -->
  <script crossorigin src="https://unpkg.com/react@18/umd/react.development.js"></script>
  <!-- React DOM -->
  <script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"></script>
  <!-- Babel -->
  <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>

  <style>
    body { font-family: Arial, sans-serif; padding: 20px; }
  </style>
</head>
<body>
  <div id="root"></div>

  <!-- React kodu burada yazılır -->
  <script type="text/babel">
    function App() {
      const [count, setCount] = React.useState(0);

      return (
        <div>
          <h1>Salam React (CDN ilə)</h1>
          <p>Sayğac: {count}</p>
          <button onClick={() => setCount(count + 1)}>Artır</button>
        </div>
      );
    }

    const root = ReactDOM.createRoot(document.getElementById('root'));
    root.render(<App />);
  </script>
</body>
</html>
