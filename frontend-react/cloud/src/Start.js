import React, { Component } from 'react';
import { Button } from "reactstrap";
import { Navigate } from "react-router-dom";
import './index.css';


export default class Home extends Component {
  state = {
    navigate: false,
  };

  onLogoutHandler = () => {
    localStorage.clear();
    this.setState({
      navigate: true,
    });
  };
  render() {
    const user = JSON.parse(localStorage.getItem("userData"));
    if(!user){
      return <h1> usuario no encontrado</h1>
  }
    const { navigate } = this.state;
    if (navigate) {
      return <Navigate to="/" push={true} />;
    }
    return (
      <div className="container  border">
        <h3> HomePage</h3>
        <div className="row">
          <div className="col-xl-9 col-sm-12 col-md-9 text-dark">
            <h5> Welcome, {user.name} </h5> You have Logged in
            successfully.
          </div>
          <div className="col-xl-3 col-sm-12 col-md-3">
            <Button
              className="btn btn-primary text-right"
              onClick={this.onLogoutHandler}
            >
              Logout
            </Button>
          </div>
        </div>
      </div>
    );
  }
}
