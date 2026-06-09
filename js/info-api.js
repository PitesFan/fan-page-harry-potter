document.addEventListener("DOMContentLoaded", () => {
  const booksGrid = document.querySelector("#books-grid");
  const moviesGrid = document.querySelector("#movies-grid");

  if (!booksGrid || !moviesGrid) {
    return;
  }

  const samplePost =
    booksGrid.querySelector(".post") || moviesGrid.querySelector(".post");
  const isDarkTheme = samplePost?.classList.contains("white-bg") ?? true;
  const postBgClass = isDarkTheme ? "white-bg" : "black-bg";
  const titleClass = isDarkTheme ? "black" : "white";
  const descriptionClass = isDarkTheme ? "dark-gray" : "light-gray";
  const badgeClass = "accent";
  const fallbackImage = "images/post-img-test.jpg";

  const labels = window.infoLabels || {
    book: "Book",
    movie: "Movie",
  };

  const truncate = (text, maxLength) => {
    if (!text) {
      return "";
    }
    if (text.length <= maxLength) {
      return text;
    }
    return `${text.slice(0, maxLength).trimEnd()}…`;
  };

  const buildBookDescription = (attributes) => {
    const parts = [];
    if (attributes.author) {
      parts.push(`Author: ${attributes.author}`);
    }
    if (attributes.release_date) {
      parts.push(`Released: ${attributes.release_date}`);
    }
    if (attributes.pages) {
      parts.push(`Pages: ${attributes.pages}`);
    }
    return parts.length
      ? `${parts.join(" • ")}\n${truncate(attributes.summary, 180)}`
      : truncate(attributes.summary, 200);
  };

  const buildMovieDescription = (attributes) => {
    const parts = [];
    if (Array.isArray(attributes.directors) && attributes.directors.length) {
      parts.push(`Director: ${attributes.directors.join(", ")}`);
    }
    if (attributes.release_date) {
      parts.push(`Released: ${attributes.release_date}`);
    }
    if (attributes.running_time) {
      parts.push(`Runtime: ${attributes.running_time}`);
    }
    if (attributes.rating) {
      parts.push(`Rating: ${attributes.rating}`);
    }
    return parts.length
      ? `${parts.join(" • ")}\n${truncate(attributes.summary, 180)}`
      : truncate(attributes.summary, 200);
  };

  const createPost = (item) => {
    const attributes = item.attributes || {};
    const isBook = item.kind === "book";
    const wiki = attributes.wiki;
    const image = isBook ? attributes.cover : attributes.poster;
    const title =
      attributes.title || (isBook ? "Untitled book" : "Untitled movie");
    const description = isBook
      ? buildBookDescription(attributes)
      : buildMovieDescription(attributes);
    const badgeLabel = isBook ? labels.book : labels.movie;

    const post = document.createElement(wiki ? "a" : "div");
    post.className = `post ${postBgClass}`;
    if (wiki) {
      post.href = wiki;
      post.target = "_blank";
      post.rel = "noopener noreferrer";
    }

    const imageEl = document.createElement("img");
    imageEl.className = "post-img";
    imageEl.src = image || fallbackImage;
    imageEl.alt = title;
    imageEl.onerror = () => {
      imageEl.src = fallbackImage;
    };

    const badge = document.createElement("span");
    badge.className = badgeClass;
    badge.textContent = badgeLabel;

    const titleEl = document.createElement("h4");
    titleEl.className = titleClass;
    titleEl.textContent = title;

    const descriptionEl = document.createElement("p");
    descriptionEl.className = descriptionClass;
    descriptionEl.style.whiteSpace = "pre-line";
    descriptionEl.textContent = description;

    post.append(imageEl, badge, titleEl, descriptionEl);

    return post;
  };

  const renderFallback = (grid, titleText) => {
    const fallback = document.createElement("div");
    fallback.className = `post ${postBgClass}`;

    const title = document.createElement("h4");
    title.className = titleClass;
    title.textContent = titleText;

    const description = document.createElement("p");
    description.className = descriptionClass;
    description.textContent =
      "Please check your connection and try again later.";

    fallback.append(title, description);
    grid.replaceChildren(fallback);
  };

  const fetchResource = async (endpoint) => {
    const url = new URL(endpoint);
    url.search = new URLSearchParams({
      "page[number]": "1",
      "page[size]": "50",
      sort: "release_date",
    }).toString();

    const response = await fetch(url);
    if (!response.ok) {
      throw new Error(`Request failed for ${endpoint}`);
    }
    const data = await response.json();
    return (data.data || []).filter(
      (entry) => entry.attributes && entry.attributes.title,
    );
  };

  const populateSection = (grid, items, fallbackTitle) => {
    if (!items.length) {
      renderFallback(grid, fallbackTitle);
      return;
    }
    grid.replaceChildren(...items.map(createPost));
  };

  Promise.allSettled([
    fetchResource("https://api.potterdb.com/v1/books"),
    fetchResource("https://api.potterdb.com/v1/movies"),
  ]).then(([booksResult, moviesResult]) => {
    if (booksResult.status === "fulfilled") {
      populateSection(
        booksGrid,
        booksResult.value.map((entry) => ({ ...entry, kind: "book" })),
        "Unable to load books",
      );
    } else {
      renderFallback(booksGrid, "Unable to load books");
    }

    if (moviesResult.status === "fulfilled") {
      populateSection(
        moviesGrid,
        moviesResult.value.map((entry) => ({ ...entry, kind: "movie" })),
        "Unable to load movies",
      );
    } else {
      renderFallback(moviesGrid, "Unable to load movies");
    }
  });
});
