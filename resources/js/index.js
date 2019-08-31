import React, { Component } from "react";
import ReactDOM from "react-dom";
import { BrowserRouter as Router, Route } from 'react-router-dom';

export default class Index extends Component {
    render() {
        return (
            <Router>
                <Navbar />
                <Route exact path="/" component={Example} />
            </Router>
        );
    }
}

if (document.getElementById("app")) {
    ReactDOM.render(<Index />, document.getElementById("app"));
}
