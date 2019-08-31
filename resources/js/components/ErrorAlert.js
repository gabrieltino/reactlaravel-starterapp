import React from "react";

export default function ErrorAlert(props) {
    return (
        <div className="alert alert-danger" role="alert">
            {props.message}
        </div>
    );
}
