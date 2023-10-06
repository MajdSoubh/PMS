import React from "react";

function stringToColor(str) {
  let hash = 0;
  for (let i = 0; i < str.length; i++) {
    hash = str.charCodeAt(i) + ((hash << 5) - hash);
  }
  let color = "#";
  for (let i = 0; i < 3; i++) {
    const value = (hash >> (i * 8)) & 0xff;
    color += ("00" + value.toString(16)).substr(-2);
  }
  return color;
}

const Avatar = ({ name }) => {
  const initials = name
    .split(" ")
    .map((n) => n.charAt(0))
    .join("");

  let bgColor = localStorage.getItem(name);

  if (!bgColor) {
    bgColor = stringToColor(name);
    localStorage.setItem(name, bgColor);
  }

  const style = {
    backgroundColor: bgColor,
    color: "#fff",
    borderRadius: "50%",
    width: "50px",
    height: "50px",
    display: "flex",
    alignItems: "center",
    justifyContent: "center",
    fontSize: "20px",
    padding: "1rem",
  };

  return <div style={style}>{initials}</div>;
};

export default Avatar;
