/*
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 * @copyright 2018 Thibault Colette
 */

import React from 'react';
import 'isomorphic-fetch';
import Grid from "@material-ui/core/es/Grid/Grid";
import withStyles from "@material-ui/core/es/styles/withStyles";
import styles from "../../Theme/form.css";
import {translate} from "react-i18next";

class NotFoundPage extends React.Component
{
    render() {
        return (
            <Grid container>
                notfound
            </Grid>
        );
    }
}

export default translate('admin.user')
(
    withStyles(styles, {withTheme: true})
    (
        NotFoundPage
    )
);