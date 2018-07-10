/*
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 * @copyright 2018 Thibault Colette
 */

import React from 'react';
import Spinner from '../../Component/Core/spinner';
import Query from "react-apollo/Query";
import logoutGql from "../../Gql/User/logout";
import {URL_LOGIN, URL_PROFIL} from "../../routes";
import {withApollo} from "react-apollo/index";
import styles from "../../Theme/form.css";
import withStyles from '@material-ui/core/es/styles/withStyles';
import {translate} from "react-i18next";

class UserLogoutPage extends React.Component
{
    render() {
        const {i18n, client, history} = this.props;

        return (
            <Query query={logoutGql}>
                {({ loading }) => {
                    if (loading) return <Spinner plainPage={true}/>;

                    client.onResetStore(function() {
                        history.push('/' + i18n.language + URL_LOGIN);
                    });
                    client.resetStore();

                    return <Spinner plainPage={true}/>;
                }}
            </Query>
        );
    }
}

export default translate()
(
    withApollo(
        withStyles(styles, {withTheme: true})
        (
            UserLogoutPage
        )
    )
);
