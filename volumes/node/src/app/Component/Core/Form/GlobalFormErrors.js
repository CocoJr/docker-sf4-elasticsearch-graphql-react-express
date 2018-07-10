/*
 * @author Thibault Colette <thibaultcolette06@hotmail.fr>
 * @copyright 2018 Thibault Colette
 */

import React from "react";
import CustomizedSnackbars from '../snackbar';

class GlobalFormErrors extends React.Component
{
    render() {
        const {errors} = this.props;

        let {t} = this.props;
        if (!t) {
            t = function(m) { return m; }
        }

        if (!errors) {
            return null;
        }

        let ret = [];
        for (let i in errors) {
            let err = errors[i];
            if (!err || err.key) {
                continue;
            }

            ret.push(
                <CustomizedSnackbars
                    key={i}
                    variant={"error"}
                    message={<div>{t(err.message)}</div>}
                />
            );
        }

        return ret;
    };
}

export default GlobalFormErrors;
