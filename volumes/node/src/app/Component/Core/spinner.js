/*
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 * @copyright 2018 Thibault Colette
 */

import React from 'react';
import PropTypes from 'prop-types';
import CircularProgress from "@material-ui/core/es/CircularProgress/CircularProgress";
import withStyles from "@material-ui/core/es/styles/withStyles";
import styles from "../../Theme/app.css";
import Grid from "@material-ui/core/es/Grid/Grid";

class Spinner extends React.Component
{
    render() {
        const { classes, size, plainPage } = this.props;

        let progressBar = <CircularProgress className={classes.progress} size={size} />;

        if (plainPage) {
            return (
                <Grid container
                      justify="center"
                      alignItems="center"
                      className={classes.plainPage}
                >
                    {progressBar}
                </Grid>
            );
        }

        return (
            <div>
                {progressBar}
            </div>
        );
    }
}

Spinner.propTypes = {
    classes: PropTypes.object.isRequired,
    size: PropTypes.number,
    plainPage: PropTypes.bool,
};

Spinner.defaultProps = {
    size: 64,
    plainPage: false,
};

export default withStyles(styles, {withTheme: true})
(
    Spinner
);