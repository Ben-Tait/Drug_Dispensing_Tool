<!DOCTYPE html>
<html>
<head>
    <title>Search and Fill Textfield</title>
    <style>
        /* Add some basic styling to the search results */
        #searchResults {
            list-style-type: none;
            padding: 0;
        }

        /* Add a hover effect to the search results */
        #searchResults li:hover {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <label for="search">Search:</label>
    <input type="text" id="search" onkeyup="search()">
    
    <ul id="searchResults">
        <!-- Search results will be dynamically populated here -->
    </ul>
    
    <label for="selectedValue">Selected Value:</label>
    <input type="text" id="selectedValue">
    
    <script>
        // Sample data for the search
        const data = ["Apple", "Banana", "Orange", "Grapes", "Pear", "Pineapple", "Watermelon"];

        function search() {
            const input = document.getElementById('search').value.toLowerCase();
            const searchResults = document.getElementById('searchResults');
            searchResults.innerHTML = '';

            data.forEach(item => {
                const lowerCasedItem = item.toLowerCase();
                if (lowerCasedItem.includes(input)) {
                    const li = document.createElement('li');
                    li.textContent = item;
                    li.onclick = function() {
                        document.getElementById('selectedValue').value = item;
                        searchResults.innerHTML = '';
                    };
                    li.style.cursor = 'pointer'; // Change cursor to a pointer on hover
                    searchResults.appendChild(li);
                }
            });
        }
    </script>
</body>
</html>
