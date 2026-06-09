document.addEventListener("DOMContentLoaded", () => {
  const postsGrid = document.querySelector(".posts-grid");

  if (!postsGrid) {
    return;
  }

  const firstPost = postsGrid.querySelector(".post");
  const isDarkTheme = firstPost?.classList.contains("white-bg") ?? true;
  const postBgClass = isDarkTheme ? "white-bg" : "black-bg";
  const titleClass = isDarkTheme ? "black" : "white";
  const descriptionClass = isDarkTheme ? "dark-gray" : "light-gray";
  const fallbackImage = "images/post-img-test.jpg";

  const createPost = (article) => {
    const post = document.createElement("a");
    post.className = `post ${postBgClass}`;
    post.href = article.url;
    post.target = "_blank";
    post.rel = "noopener noreferrer";

    const image = document.createElement("img");
    image.className = "post-img";
    image.src = article.urlToImage || fallbackImage;
    image.alt = article.title || "Harry Potter news";
    image.onerror = () => {
      image.src = fallbackImage;
    };

    const title = document.createElement("h4");
    title.className = `post-title ${titleClass}`;
    title.textContent = article.title || "Harry Potter news";

    const description = document.createElement("p");
    description.className = `post-description ${descriptionClass}`;
    description.textContent =
      article.description ||
      article.content ||
      "Read the latest popular Harry Potter story.";

    post.append(image, title, description);

    return post;
  };

  const parseEnv = (text) => {
    const env = {};
    text.split(/\r?\n/).forEach((line) => {
      const trimmed = line.trim();
      if (!trimmed || trimmed.startsWith("#")) return;
      const eq = trimmed.indexOf("=");
      if (eq === -1) return;
      const key = trimmed.slice(0, eq).trim();
      const value = trimmed
        .slice(eq + 1)
        .trim()
        .replace(/^['"]|['"]$/g, "");
      env[key] = value;
    });
    return env;
  };

  const loadApiKey = () =>
    fetch("js/.env", { cache: "no-store" })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Failed to load .env");
        }
        return response.text();
      })
      .then((text) => parseEnv(text))
      .then((env) => env.API_KEY);

  const fetchPosts = (apiKey) => {
    const endpoint = new URL("https://newsapi.org/v2/everything");
    endpoint.search = new URLSearchParams({
      q: '"Harry Potter movies"',
      language: "en",
      sortBy: "popularity",
      pageSize: "15",
      apiKey,
    }).toString();

    return fetch(endpoint).then((response) => {
      if (!response.ok) {
        throw new Error("NewsAPI request failed");
      }
      return response.json();
    });
  };

  loadApiKey()
    .then((apiKey) => {
      if (!apiKey) {
        throw new Error("API_KEY missing in .env");
      }
      return fetchPosts(apiKey);
    })
    .then((data) => {
      const articles = (data.articles || []).filter(
        (article) => article.title && article.url,
      );

      if (!articles.length) {
        return;
      }

      postsGrid.replaceChildren(...articles.slice(0, 15).map(createPost));
    })
    .catch(() => {
      postsGrid.dataset.newsapiStatus = "fallback";
    });
});
