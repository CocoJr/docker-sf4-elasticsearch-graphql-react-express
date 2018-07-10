import React from 'react';
import {isUser} from "../../Util/core";
import {Redirect, Route} from "react-router-dom";
import {URL_LOGIN} from "../../routes";

class UserProtectedRoute extends React.Component {
    render() {
        const { component: Component} = this.props;

        let newProps = Object.assign({}, this.props);
        newProps.component = undefined;

        return (
            <Route {...newProps} render={(props) => (
                isUser(newProps.user)
                    ? <Component {...props} />
                    : <Redirect to={URL_LOGIN} />
            )} />
        );
    }
}

export default UserProtectedRoute;